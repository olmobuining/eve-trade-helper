<?php

namespace Tests\Feature;

use App\Order;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class OrderTest extends TestCase
{
    /**
     * Default order type should be 'sell'
     *
     * @test
     * @return Order
     */
    public function testDefaultOrderType()
    {
        $order = new Order();
        $this->assertFalse($order->isBuyOrder());
        $this->assertEquals('sell', $order->getOrderType());

        return $order;
    }

    /**
     * @param Order $order
     * @depends testDefaultOrderType
     * @test
     * @return void
     */
    public function testTypeName(Order $order)
    {
        $this->assertEmpty($order->getInventoryName());
        $order->type_id = 2;
        $this->assertEquals('Corporation', $order->getInventoryName());
        // Check if the property now has been set after receiving it from the database.
        $this->assertEquals('Corporation', $order->type_name);
    }

}
