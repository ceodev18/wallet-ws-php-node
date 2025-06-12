<?php

namespace Tests\Feature;

use PHPUnit\Framework\MockObject\MockObject;
use Tests\TestCase;

class WalletSoapTest extends TestCase
{
    /** @test */
    public function registerClient_shouldReturnMockedSuccess()
    {
        $client = $this->mockSoapClient('registerClient', [
            'success' => true,
            'cod_error' => '00',
            'message_error' => 'Mocked client registered',
            'data' => null
        ]);

        $response = $client->__soapCall('registerClient', [
            'document' => '12345678',
            'name' => 'Test User',
            'email' => 'mock@example.com',
            'phone' => '999999999'
        ]);

        $decoded = json_decode($response, true);
        $this->assertTrue($decoded['success']);
        $this->assertEquals('00', $decoded['cod_error']);
    }

    /** @test */
    public function loadWallet_shouldReturnMockedSuccess()
    {
        $client = $this->mockSoapClient('loadWallet', [
            'success' => true,
            'cod_error' => '00',
            'message_error' => 'Mocked wallet loaded',
            'data' => null
        ]);

        $response = $client->__soapCall('loadWallet', [
            'document' => '12345678',
            'phone' => '999999999',
            'amount' => 100
        ]);

        $decoded = json_decode($response, true);
        $this->assertTrue($decoded['success']);
    }

    /** @test */
    public function checkBalance_shouldReturnMockedBalance()
    {
        $client = $this->mockSoapClient('checkBalance', [
            'success' => true,
            'cod_error' => '00',
            'message_error' => 'Mocked balance check',
            'data' => ['balance' => 150.50]
        ]);

        $response = $client->__soapCall('checkBalance', [
            'document' => '12345678',
            'phone' => '999999999',
        ]);

        $decoded = json_decode($response, true);
        $this->assertEquals(150.50, $decoded['data']['balance']);
    }

    /** @test */
    public function makePurchase_shouldReturnMockedSessionId()
    {
        $client = $this->mockSoapClient('makePurchase', [
            'success' => true,
            'cod_error' => '00',
            'message_error' => 'Mocked purchase',
            'data' => ['session_id' => 'mocked-session-id']
        ]);

        $response = $client->__soapCall('makePurchase', [
            'document' => '12345678',
            'phone' => '999999999',
        ]);

        $decoded = json_decode($response, true);
        $this->assertEquals('mocked-session-id', $decoded['data']['session_id']);
    }

    /** @test */
    public function confirmPayment_shouldReturnMockedSuccess()
    {
        $client = $this->mockSoapClient('confirmPayment', [
            'success' => true,
            'cod_error' => '00',
            'message_error' => 'Mocked payment confirmed',
            'data' => null
        ]);

        $response = $client->__soapCall('confirmPayment', [
            'session_id' => 'mocked-session-id',
            'token' => '123456',
        ]);

        $decoded = json_decode($response, true);
        $this->assertTrue($decoded['success']);
    }

    private function mockSoapClient(string $method, array $response): \SoapClient|MockObject
    {
        $mock = $this->createMock(\SoapClient::class);
        $mock->expects($this->once())
             ->method('__soapCall')
             ->with($method, $this->anything())
             ->willReturn(json_encode($response));
        return $mock;
    }
}
