<?php

namespace Meisterwerk\BqUtils\MW;

class MwBqUtil
{
    /**
     * @deprecated see MwBqUtil->getOrderLinkHtmlV4, API version V1 will no longer be supported
     *
     * Structure of $order:
     * [
     *      "id" => "1234",
     *      "properties_attributes": [
     *         "sprache": "English",
     *          ...
     *      ],
     *      "number": "1234",
     *      ...
     * ]
     */
    public static function getOrderLinkHtmlV1($order): string {
        return "<a href=\"https://rentshop.booqable.com/orders/{$order->id}\">#{$order->number}</a>";
    }

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