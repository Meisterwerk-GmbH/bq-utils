<?php

namespace Meisterwerk\BqUtils\MW;

class MwBqUtil
{
    public static function getOrderLinkHtmlV4($order): string {
        $orderId = $order->data->id;
        $orderNumber = $order->data->attributes->number;
        return "<a href=\"https://rentshop.booqable.com/orders/{$orderId}\">#{$orderNumber}</a>";
    }

    public static function getOrderLinkHtmlV1($order): string {
        return "<a href=\"https://rentshop.booqable.com/orders/{$order->id}\">#{$order->number}</a>";
    }
}