<?php

namespace Tests\Unit;

use App\Models\Delivery;
use PHPUnit\Framework\TestCase;

class DeliveryTest extends TestCase
{
    public function test_a_delivery_with_no_tracking_limits_is_trackable(): void
    {
        $delivery = new Delivery(['tracking_token' => 'test']);
        $this->assertTrue($delivery->isTrackable());
    }
}
