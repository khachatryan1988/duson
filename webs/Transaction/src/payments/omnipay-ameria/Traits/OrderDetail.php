<?php

namespace Omnipay\Ameria\Traits;

use Omnipay\Ameria\Constants\OrderStatus;

/**
 * Trait OrderDetails
 * @package Omnipay\Ameria\Traits
 */
trait OrderDetail
{
    /**
     * Is the payment started?
     * @return bool
     */
    public function isStarted()
    {
        return $this->getOrderStatus() == OrderStatus::PAYMENT_STARTED;
    }

    /**
     * Is the payment pre authorized ?
     * @return bool
     */
    public function isPreAuthorized()
    {
        return $this->getOrderStatus() == OrderStatus::PAYMENT_APPROVED;
    }

    /**
     * Is the payment declined?
     * @return bool
     */
    public function isDeclined()
    {
        return $this->getOrderStatus() == OrderStatus::PAYMENT_DECLINED;
    }

    /**
     * Is the payment deposited?
     * @return bool
     */
    public function isDeposited()
    {
        return $this->getOrderStatus() == OrderStatus::PAYMENT_DEPOSITED;
    }

    /**
     * Is the payment refunded?
     * @return bool
     */
    public function isRefunded()
    {
        return $this->getOrderStatus() == OrderStatus::PAYMENT_REFUNDED;
    }

    /**
     * Is the payment authorized?
     * @return bool
     */
    public function isAuthorized()
    {
        return $this->getOrderStatus() == OrderStatus::PAYMENT_AUTOAUTHORIZED;
    }

    /**
     * Is the payment void?
     * @return bool
     */
    public function isVoid()
    {
        return $this->getOrderStatus() == OrderStatus::PAYMENT_VOID;
    }
}