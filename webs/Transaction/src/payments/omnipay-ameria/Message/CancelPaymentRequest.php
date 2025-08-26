<?php

namespace Omnipay\Ameria\Message;

/**
 * Class CancelPaymentRequest
 * @package Omnipay\Ameria\Message
 */
class CancelPaymentRequest extends AbstractRequest
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
        ]);
    }

    /**
     * @return string
     */
    public function getEndpoint()
    {
        return $this->getUrl().'CancelPayment';
    }
}