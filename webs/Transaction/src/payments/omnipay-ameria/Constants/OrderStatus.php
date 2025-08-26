<?php

namespace Omnipay\Ameria\Constants;

/**
 * Class OrderStatus
 * @package Omnipay\Ameria\Constants
 */
class OrderStatus
{
    const PAYMENT_STARTED = 0;
    const PAYMENT_APPROVED = 1;
    const PAYMENT_DECLINED = 6;
    const PAYMENT_DEPOSITED = 2;
    const PAYMENT_REFUNDED = 4;
    const PAYMENT_AUTOAUTHORIZED = 5;
    const PAYMENT_VOID = 3;
}