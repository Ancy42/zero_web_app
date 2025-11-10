<?php

namespace App\Repositories;

use Abedin\Maker\Repositories\Repository;
use App\Enums\OrderStatus;
use App\Models\Driver;
use App\Models\DriverOrder;
use App\Models\Order;

class DriverOrderRepository extends Repository
{
    /**
     * base method
     *
     * @method model()
     */
    public static function model()
    {
        return DriverOrder::class;
    }

    public static function storeByRequest(Driver $driver, Order $order): DriverOrder
    {
        $assignFor = OrderStatus::DELIVERED->value;

        return self::create([
            'driver_id' => $driver->id,
            'order_id' => $order->id,
            'assign_for' => $assignFor,
            'is_completed' => false,
        ]);
    }
}
