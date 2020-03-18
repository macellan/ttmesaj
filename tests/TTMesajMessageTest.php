<?php

namespace Macellan\TTMesaj;

use Carbon\Carbon;
use PHPUnit\Framework\TestCase;

class TTMesajMessageTest extends TestCase
{
    /** @test */
    public function itCanBeInstantiate()
    {
        $additionalNumbers = [
            '905333333332',
            '905333333334',
            '905333333335',
        ];

        $instance = TTMesajMessage::create()
            ->setBody('TEST_BODY')
            ->addNumbers($additionalNumbers)
            ->setSendTime(Carbon::now())
            ->setEndTime(Carbon::now()->addDay());

        $this->assertInstanceOf(TTMesajMessage::class, $instance);
    }
}
