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
        $this->assertTrue($response->isRedirect());
        $this->assertNull($response->getTransactionReference());
        $this->assertNull($response->getMessage());
    }

    /** @test */
    // public function authorize()
    // {
    //     $request = $this->gateway->authorize();
    //     $this->assertInstanceOf('Omnipay\Laybuy\Message\AuthorizeRequest', $request);
    // }

    /** @test */
    // public function authorizeRequest()
    // {
    //     $this->setMockHttpResponse('OrderSuccess.txt');

    //     $response = $this->gateway->authorize()->send();
    //     $contents = (string) $response->getData();

    //     $expected = [
    //         "result"      => "SUCCESS",
    //         "token"       => "frOilqUU0DboUCiyRtnzH1VdBXnrj7kD39NWhgsD",
    //         "paymentUrl"  => "https://payment.laybuy.com/pay/frOilqUU0DboUCiyRtnzH1VdBXnrj7kD39NWhgsD"
    //     ];

    //     $this->assertTrue($response->isSuccessful());
    //     $this->assertEquals($expected, $contents[0]);
    // }

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
