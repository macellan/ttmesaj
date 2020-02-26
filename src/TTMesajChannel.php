<?php

namespace Macellan\TTMesaj;

use Illuminate\Notifications\Notification;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Macellan\TTMesaj\Exceptions\CouldNotSendNotification;
use SoapClient;

class TTMesajChannel
{
    /**
     * Login to API endpoint.
     *
     * @var string
     */
    protected $username;

    /**
     * Password to API endpoint.
     *
     * @var string
     */
    protected $password;

    /**
     * API endpoint wsdl url.
     *
     * @var string
     */
    protected $wsdlEndpoint;

    /**
     * Registered sender. Should be requested in TTMesaj user's page.
     *
     * @var string
     */
    protected $origin;

    /**
     * Debug flag. If true, messages send/result wil be stored in Laravel log.
     *
     * @var bool
     */
    protected $debug;

    /**
     * If true, will run.
     *
     * @var bool
     */
    protected $enable;

    /**
     * Sandbox mode flag. If true, endpoint API will not be invoked, useful for dev purposes.
     *
     * @var bool
     */
    protected $sandboxMode;

    public function __construct(array $config = [])
    {
        $this->username = Arr::get($config, 'username');
        $this->password = Arr::get($config, 'password');
        $this->wsdlEndpoint = Arr::get($config, 'wsdlEndpoint');
        $this->origin = Arr::get($config, 'origin');
        $this->debug = Arr::get($config, 'debug', false);
        $this->enable = Arr::get($config, 'enable', true);
        $this->sandboxMode = Arr::get($config, 'sandboxMode', false);
    }

    /**
     * @return SoapClient
     * @throws CouldNotSendNotification
     */
    protected function getClient()
    {
        try {
            return new SoapClient($this->wsdlEndpoint);
        } catch (\Exception $exception) {
            throw CouldNotSendNotification::couldNotCommunicateWithEndPoint($exception);
        }
    }

    /**
     * Send the given notification.
     *
     * @param mixed $notifiable
     * @param Notification $notification
     *
     * @return void|array
     * @throws CouldNotSendNotification
     */
    public function send($notifiable, Notification $notification)
    {
        if (! $this->enable) {
            if ($this->debug) {
                Log::info('TTMesaj is disabled');
            }

            return;
        }

        /** @var TTMesajMessage $message */
        $message = $notification->toTTMesaj($notifiable);
        if (is_string($message)) {
            $message = new TTMesajMessage($message);
        }

        $sms = [
            'username' => $this->username,
            'password' => $this->password,
            'numbers' => $notifiable->routeNotificationFor('ttmesaj'),
            'message' => $message->body,
            'origin' => $this->origin,
            'sd' => $message->sendTime,
            'ed' => $message->endTime,
        ];

        if ($this->debug) {
            Log::info('TTMesaj sending sms - '.print_r($sms, true));
        }

        if ($this->sandboxMode) {
            return;
        }

        $client = $this->getClient();
        $result = $client->sendSingleSMS($sms);

        if ($this->debug) {
            Log::info('TTMesaj send result - '.print_r($result->sendSingleSMSResult, true));
        }

        return $result;
    }
}
