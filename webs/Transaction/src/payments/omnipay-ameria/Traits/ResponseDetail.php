<?php
namespace Omnipay\Ameria\Traits;

use Omnipay\Ameria\Constants\ResponseStatus;

/**
 * Trait ResponseDetails
 * @package Omnipay\Ameria\Traits
 */
trait ResponseDetail
{
    /**
     * Is the response Completed?
     * @return bool
     */
    public function isCompleted()
    {
        return $this->getCode() == ResponseStatus::SUCCESSFUL;
    }

    /**
     * Is the response no error?
     * @return bool
     */
    public function isNotError()
    {
        return $this->getCode() == ResponseStatus::NO_ERROR;
    }
}