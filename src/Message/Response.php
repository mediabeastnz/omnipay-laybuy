<?php

namespace Omnipay\Laybuy\Message;

use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RequestInterface;

class Response extends AbstractResponse
{

    /**
     * Response constructor.
     *
     * @param \Omnipay\Common\Message\RequestInterface $request
     * @param mixed                                    $data
     */
    public function __construct(RequestInterface $request, $data)
    {
        parent::__construct($request, $data);
    }

    /**
     * @return bool
     */
    public function isSuccessful()
    {
        if (array_key_exists('result', $this->data) && $this->data['result'] == 'SUCCESS') {
            return true;
        }

        return false;
    }

    public function getMessage()
    {
        if (isset($this->data['result']) && $this->data['result'] == 'ERROR') {
            return $this->data['error'];
        } elseif (isset($this->data['result']) && $this->data['result'] == 'SUCCESS') {
            return "SUCCESS";
        }

        return null;

    }

    public function getTransactionReference()
    {
        if (isset($this->data['orderId'])) {
            return $this->data['orderId'];
        } elseif (isset($this->data['paymentUrl'])){
            return $this->data['paymentUrl'];
        }

        return null;

    }

}
