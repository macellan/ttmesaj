<?php

namespace Macellan\TTMesaj;

use Illuminate\Notifications\Notifiable;
use Illuminate\Notifications\Notification;
use Mockery;
use PHPUnit\Framework\TestCase;

class TTMesajChannelTest extends TestCase
{
    /**
     * @var Notification|Mockery\MockInterface
     */
    protected $testNotification;

    protected function tearDown(): void
    {
        Mockery::close();
    }

    /** @test */
    public function it_can_be_instantiate()
    {
        $testConfig = [
            'login' => 'TEST_LOGIN',
            'password' => 'TEST_PASSWORD',
            'wsdlEndpoint' => 'https://ws.ttmesaj.com/Service1.asmx?WSDL',
            'sender' => 'TEST_SENDER',
            'enable' => true,
            'debug' => false,
            'sandboxMode' => false,
        ];
        $instance = new TTMesajChannel($testConfig);

        $this->assertInstanceOf(TTMesajChannel::class, $instance);
    }

    /** @test */
    public function it_sends_a_notification()
    {
        $testConfig = [
            'login' => 'TEST_LOGIN',
            'password' => 'TEST_PASSWORD',
            'wsdlEndpoint' => 'https://ws.ttmesaj.com/Service1.asmx?WSDL',
            'sender' => 'TEST_SENDER',
            'enable' => true,
            'debug' => false,
            'sandboxMode' => false,
        ];

        $testClient = Mockery::mock(\SoapClient::class);
        $testClient->shouldReceive('sendSingleSMS')->andReturn([
            'OK'
        ]);

        $testChannel = Mockery::mock(TTMesajChannel::class, [$testConfig])->makePartial()->shouldAllowMockingProtectedMethods();
        $testChannel->shouldReceive('getClient')->andReturn($testClient);

        $this->assertIsArray($testChannel->send(new TestNotifiable(), new TestNotification()));
    }

    /** @test */
    public function it_do_not_invoke_api_in_sandbox_mode()
    {
        $testConfig = [
            'login' => 'TEST_LOGIN',
            'password' => 'TEST_PASSWORD',
            'wsdlEndpoint' => 'https://ws.ttmesaj.com/Service1.asmx?WSDL',
            'sender' => 'TEST_SENDER',
            'debug' => false,
            'sandboxMode' => true,
        ];

        $testClient = Mockery::spy(\SoapClient::class);

        $testChannel = Mockery::mock(TTMesajChannel::class, [$testConfig])->makePartial()->shouldAllowMockingProtectedMethods();
        $testChannel->shouldReceive('getClient')->andReturn($testClient);

        $this->assertNull($testChannel->send(new TestNotifiable(), new TestNotification()));
        $testClient->shouldNotHaveReceived('SendSMS');
    }
}

class TestNotifiable
{
    use Notifiable;

    public function routeNotificationForTTMesaj()
    {
        return 'TEST_RECIPIENT';
    }
}

class TestNotification extends Notification
{
    public function toTTMesaj($notifiable)
    {
        return new TTMesajMessage('TEST_BODY');
    }
}
