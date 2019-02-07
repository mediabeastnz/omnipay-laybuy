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
        return array(
            'token' => $this->getToken()
        );
    }

    /**
     * @return string
     */
    public function getEndpoint()
    {
        return parent::getEndpoint() . '/order/confirm';
    }
}
