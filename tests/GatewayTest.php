<?php

namespace Omnipay\Laybuy;

use Omnipay\Tests\GatewayTestCase;

/**
 * @property Gateway gateway
 */
class GatewayTest extends GatewayTestCase
{
    /**
     * @var \Omnipay\Laybuy\Gateway
     */
    protected $gateway;

    /**
     * @var array
     */
    protected $options;


    public function setUp()
    {
        parent::setUp();

        $this->gateway = new Gateway($this->getHttpClient(), $this->getHttpRequest());

        $this->options = array(
            'testMode' => true,
            'amount' => '10.00',
            'returnUrl' => 'https://www.example.com/return',
        );

    }

    public function testAuthorizeSuccess()
    {
        $this->setMockHttpResponse('OrderSuccess.txt');

        $response = $this->gateway->authorize($this->options)->send();

        $this->_testSuccessfulPurchase($response);
    }

    private function _testSuccessfulPurchase($response)
    {
        $this->assertFalse($response->isSuccessful());
        $this->assertNull($response->getTransactionReference());
        $this->assertNull($response->getMessage());
    }

    public function testPurchase()
    {
        $request = $this->gateway->purchase(array('amount' => '10.00'));

        $this->assertInstanceOf('Omnipay\Laybuy\Message\PurchaseRequest', $request);
        $this->assertSame('10.00', $request->getAmount());
    }

    public function testPurchaseReturn()
    {
        $request = $this->gateway->completePurchase(array('amount' => '10.00'));

        $this->assertInstanceOf('Omnipay\Laybuy\Message\CompletePurchaseRequest', $request);
        $this->assertSame('10.00', $request->getAmount());
    }

}
