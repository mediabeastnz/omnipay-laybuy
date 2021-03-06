<?php

namespace Omnipay\Laybuy\Message;

class CompletePurchaseRequest extends AuthorizeRequest
{
    /**
     * Get the raw data array for this message. The format of this varies from gateway to
     * gateway, but will usually be either an associative array, or a SimpleXMLElement.
     *
     * @return mixed
     */
    public function getData()
    {
        $token = $this->getToken() ? $this->getToken() : $this->httpRequest->query->get('token');
        return array(
            'currency' => $this->getCurrency(),
            'amount' => $this->getAmount(),
            'token' => $token
        );
    }

    /**
     * @return string
     */
    public function getEndpoint()
    {
        return parent::getEndpoint() . '/order/confirm';
    }

    public function getToken()
    {
        return $this->getParameter('token');
    }

    public function setToken($value)
    {
        return $this->setParameter('token', $value);
    }
}
