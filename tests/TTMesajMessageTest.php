<?php

namespace Macellan\TTMesaj;

use Carbon\Carbon;
use PHPUnit\Framework\TestCase;

class TTMesajMessageTest extends TestCase
{
    /** @test */
    public function itCanBeInstantiate()
    {
        $instance = TTMesajMessage::create()
            ->setBody('TEST_BODY')
            ->setSendTime(Carbon::now())
            ->setEndTime(Carbon::now()->addDay());
        $this->assertInstanceOf(TTMesajMessage::class, $instance);
    }
}
