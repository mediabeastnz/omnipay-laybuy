<?php

namespace Omnipay\Laybuy\Message;

use Symfony\Component\HttpFoundation\Response as HttpResponse;

class PurchaseResponse extends Response
{

    public function getRedirectMethod()
    {
        return 'GET';
    }

    /**
     * @return bool
     */
    public function isRedirect()
    {
        return ((1 === (int) $this->data['token']) && !empty($this->data->paymentUrl));
    }

    public function getRedirectUrl()
    {
        if ($this->isRedirect()) {
            return (string) $this->data->paymentUrl;
        }
    }

    /**
     * @return string|null
     */
    public function getToken()
    {
        return isset($this->data->token) ? $this->data->token : null;
    }

    /**
     * @return string
     */
    public function getTransactionReference()
    {
        return $this->getToken();
    }

    /**
     * @return string
     */
    public function getPaymentUrl()
    {
        return isset($this->data->paymentUrl) ? $this->data->paymentUrl : null;
    }
}
