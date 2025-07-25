<?php

namespace Meisterwerk\BqUtils\MW;

class MwBqUtil
{

    /**
     * Structure of $orderData:
     * [
     *      "id" => "1234",
     *      "type": "orders",
     *      "attributes": [
     *          "number": "1234",
     *          ...,
     *          "properties": [
     *              "sprache": "English",
     *              ...
     *          ]
     *      ]
     * ]
     */
    public static function getOrderLinkHtmlV4($orderData): string {
        $orderId = $orderData->id;
        $orderNumber = $orderData->attributes->number;
        return "<a href=\"https://rentshop.booqable.com/orders/{$orderId}\">#{$orderNumber}</a>";
    }

    /**
     * Structure of $data:
     * [
     *      "id" => "1234",
     *      "number": "1234",
     *      ...
     * ]
     */
    public static function getOrderLinkHtmlHookData($data): string {
        return "<a href=\"https://rentshop.booqable.com/orders/{$data->id}\">#{$data->number}</a>";
    }
}