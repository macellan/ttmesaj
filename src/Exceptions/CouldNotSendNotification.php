<?php

namespace Macellan\TTMesaj\Exceptions;

use Exception;

class CouldNotSendNotification extends Exception
{
    /**
     * Thrown when we're unable to communicate with ttmesaj.
     *
     * @param Exception $exception
     *
     * @return CouldNotSendNotification
     */
    public static function couldNotCommunicateWithEndPoint(Exception $exception): self
    {
        return new static('The communication with endpoint failed. Reason: {$exception->getMessage()}', $exception->getCode(), $exception);
    }
}
