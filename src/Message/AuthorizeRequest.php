<?php

namespace Omnipay\Laybuy\Message;

use Omnipay\Common\Exception\InvalidResponseException;
use Omnipay\Common\Message\AbstractRequest;

class AuthorizeRequest extends AbstractRequest
{
    protected $liveEndpoint = 'https://api.laybuy.com';

    protected $testEndpoint = 'https://sandbox-api.laybuy.com';

    /**
     * @return mixed
     */
    public function getMerchantId()
    {
        return $this->getParameter('merchantId');
    }

    /**
     * @param mixed $value
     * @return $this
     * @throws \Omnipay\Common\Exception\RuntimeException
     */
    public function setMerchantId($value)
    {
        return $this->setParameter('merchantId', $value);
    }

    /**
     * @return mixed
     */
    public function getMerchantSecret()
    {
        return $this->getParameter('merchantSecret');
    }

    /**
     * @param mixed $value
     * @return $this
     * @throws \Omnipay\Common\Exception\RuntimeException
     */
    public function setMerchantSecret($value)
    {
        return $this->setParameter('merchantSecret', $value);
    }

    protected function getHttpMethod()
    {
        return 'POST';
    }

    protected function getEndpoint()
    {
        return $this->getTestMode() ? $this->testEndpoint : $this->liveEndpoint;
    }

    public function getData()
    {
        if (isset($this->data)) {
            return $this->data;
        }
    }

    public function sendData($data)
    {
        // don't throw exceptions for 4xx errors
        $this->httpClient->getEventDispatcher()->addListener(
            'request.error',
            function ($event) {
                if ($event['response']->isClientError()) {
                    $event->stopPropagation();
                }
            }
        );

        // Guzzle HTTP Client createRequest does funny things when a GET request
        // has attached data, so don't send the data if the method is GET.
        if ($this->getHttpMethod() == 'GET') {
            $httpRequest = $this->httpClient->createRequest(
                $this->getHttpMethod(),
                $this->getEndpoint() . '?' . http_build_query($data),
                array(
                    'Accept' => 'application/json',
                    'Authorization' => $this->buildAuthorizationHeader(),
                    'Content-type' => 'application/json',
                )
            );
        } else {
            $httpRequest = $this->httpClient->createRequest(
                $this->getHttpMethod(),
                $this->getEndpoint(),
                array(
                    'Accept' => 'application/json',
                    'Authorization' => $this->buildAuthorizationHeader(),
                    'Content-type' => 'application/json',
                ),
                $this->toJSON($data)
            );
        }

        try {
            $httpRequest->getCurlOptions()->set(CURLOPT_SSLVERSION, 6); // CURL_SSLVERSION_TLSv1_2 for libcurl < 7.35
            $httpResponse = $httpRequest->send();
            $responseBody = (string) $httpResponse->getBody();
            $response = json_decode($responseBody, true) ?? [];

            return $this->response = $this->createResponse($response);
        } catch (\Exception $e) {
            throw new InvalidResponseException(
                'Error communicating with payment gateway: ' . $e->getMessage(),
                $e->getCode()
            );
        }

    }

    public function toJSON($data, $options = 0)
    {
        if (version_compare(phpversion(), '5.4.0', '>=') === true) {
            return json_encode($data, $options | 64);
        }
        return str_replace('\\/', '/', json_encode($data, $options));
    }

    protected function createResponse($data)
    {
        return $this->response = new Response($this, $data);
    }

    protected function buildAuthorizationHeader()
    {
        $merchantId = $this->getMerchantId();
        $merchantSecret = $this->getMerchantSecret();

        return 'Basic ' . base64_encode($merchantId . ':' . $merchantSecret);
    }
}
