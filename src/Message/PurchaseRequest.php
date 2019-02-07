<?php

namespace Omnipay\Laybuy\Message;

use Omnipay\Common\Exception\InvalidRequestException;

class PurchaseRequest extends AuthorizeRequest
{
    /**
     * Get the raw data array for this message. The format of this varies from gateway to
     * gateway, but will usually be either an associative array, or a SimpleXMLElement.
     *
     * @return mixed
     * @throws \Omnipay\Common\Exception\InvalidRequestException
     */
    public function getData()
    {
        /** @var \Omnipay\Common\CreditCard $card */
        $card = $this->getCard();

        $givenNames = $card->getFirstName();
        $surname = $card->getLastName();

        if (empty($surname) && false !== $pos = strrpos($givenNames, ' ')) {
            $surname = substr($givenNames, $pos + 1);
            $givenNames = substr($givenNames, 0, $pos);
        }

        $returnUrl = $this->getReturnUrl();
        $cancelUrl = $this->getCancelUrl();

        $data = array(
            'amount'          => $this->getAmount(),
            'tax'             => $this->getTaxAmount(),
            'currency'        => $this->getCurrency(),
            'customer'        => array(
                'firstName'   => $givenNames,
                'lastName'    => $surname,
                'email'       => $card->getEmail(),
                'phone'       => $card->getPhone(),
            ),
            'billingAddress'  => array(
                'name'        => $card->getBillingName(),
                'address1'    => $card->getBillingAddress1(),
                'address2'    => $card->getBillingAddress2(),
                'postcode'    => $card->getBillingPostcode(),
                'country'     => $card->getBillingCountry(),
                'phone'       => $card->getBillingPhone(),
            ),
            'shippingAddress' => array(
                'name'        => $card->getShippingName(),
                'address1'    => $card->getShippingAddress1(),
                'address1'    => $card->getShippingAddress2(),
                'postcode'    => $card->getShippingPostcode(),
                'country'     => $card->getShippingCountry(),
                'phone'       => $card->getShippingPhone(),
            ),
            'items'           => $this->getItemData(),
            'returnUrl' => $returnUrl,
            'merchantReference' => $this->getTransactionReference(),
        );

        return $data;
    }


    /**
     * @return array
     * @throws \Omnipay\Common\Exception\InvalidRequestException
     */
    public function getItemData()
    {
        $items = $this->getItems();
        $itemArray = array();

        if ($items !== null) {
            /** @var \Omnipay\Common\ItemInterface $item */
            foreach ($items as $item) {
                $itemArray[] = array(
                    'id'            => $item->getName(), // TODO: create getSku() setter and getter
                    'description'   => $item->getName(),
                    'quantity'      => $item->getQuantity(),
                    'price'         => $this->formatPrice($item->getPrice())
                );
            }
        }

        return $itemArray;
    }

    /**
     * @return string
     */
    public function getEndpoint()
    {
        return parent::getEndpoint() . '/order/create';
    }

    /**
     * @param mixed $data
     * @return \Omnipay\Laybuy\Message\Response
     */
    protected function createResponse($data)
    {
        return new PurchaseResponse($this, $data);
    }

    /**
     * @param string|float|int $amount
     * @return null|string
     * @throws \Omnipay\Common\Exception\InvalidRequestException
     */
    protected function formatPrice($amount)
    {
        if ($amount) {
            if (!is_float($amount) &&
                $this->getCurrencyDecimalPlaces() > 0 &&
                false === strpos((string) $amount, '.')
            ) {
                throw new InvalidRequestException(
                    'Please specify amount as a string or float, ' .
                    'with decimal places (e.g. \'10.00\' to represent $10.00).'
                );
            }

            return $this->formatCurrency($amount);
        }

        return null;
    }
}
