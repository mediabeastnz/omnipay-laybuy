<?php

namespace Omnipay\Laybuy\Message;

class PurchaseResponse extends Response
{

    /**
     * @return string
     */
    public function getRedirectMethod()
    {
        return 'GET';
    }

    /**
     * @return bool
     */
    public function isRedirect()
    {
        return $this->isSuccessful();
    }

    /**
     * @return string|null
     */
    public function getRedirectUrl()
    {
        if ($this->isRedirect()) {
            return $this->data['paymentUrl'];
        }
        return null;
    }

    /**
     * @return string
     */
    public function getPaymentUrl()
    {
        return isset($this->data['paymentUrl']) ? $this->data['paymentUrl'] : null;
    }
}
