<?php

namespace Omnipay\Ameria\Message;

/**
 * Class RefundPaymentRequest
 * @package Omnipay\Ameria\Message
 */
class RefundPaymentRequest extends AbstractRequest
{
    /**
     * @return array|mixed
     * @throws \Omnipay\Common\Exception\InvalidRequestException
     */
    public function getData()
    {
        $this->validate('paymentId');

        return array_merge(parent::getData(), [
            'PaymentID' => $this->getPaymentId(),
            'Amount'    => $this->getAmount(),
        ]);
    }

    /**
     * @return string
     */
    public function getEndpoint()
    {
        return $this->getUrl().'RefundPayment';
    }
}