<?php

namespace Macellan\TTMesaj;

use Carbon\Carbon;

class TTMesajMessage
{
    /**
     * Message body.
     *
     * @var string
     */
    public $body;

    public $sendTime;

    public $endTime;

    public $addNumbers;

    /**
     * @param string $body
     * @return static
     */
    public static function create($body = '')
    {
        return new static($body);
    }

    /**
     * @param string $body
     */
    public function __construct(string $body)
    {
        $this->body = $body;
        $this->addNumbers = [];
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setBody(string $value)
    {
        $this->body = $value;

        return $this;
    }

    /**
     * @param Carbon $value
     * @return $this
     */
    public function setSendTime(Carbon $value)
    {
        $this->sendTime = $value->format('YmdHi');

        return $this;
    }

    /**
     * @param Carbon $value
     * @return $this
     */
    public function setEndTime(Carbon $value)
    {
        $this->endTime = $value->format('YmdHi');

        return $this;
    }

    /**
     * @param array $array
     * @return $this
     */
    public function addNumbers(array $array)
    {
        $this->addNumbers = $array;

        return $this;
    }
}
