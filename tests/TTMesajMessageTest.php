<?php

namespace Macellan\TTMesaj;

use PHPUnit\Framework\TestCase;

class TTMesajMessageTest extends TestCase
{
    /** @test */
    public function it_can_be_instantiate()
    {
        $instance = new TTMesajMessage('TEST_BODY');
        $this->assertInstanceOf(TTMesajMessage::class, $instance);
    }
}
