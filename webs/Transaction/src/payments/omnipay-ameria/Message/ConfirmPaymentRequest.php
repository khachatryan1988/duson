<?php

namespace Omnipay\Ameria\Message;

/**
 * Class ConfirmPaymentRequest
 * @package Omnipay\Ameria\Message
 */
class ConfirmPaymentRequest extends AbstractRequest
{
    /**
     * @return array|mixed
     * @throws \Omnipay\Common\Exception\InvalidRequestException
     */
    public function getData()
    {
        $this->validate('paymentId', 'amount');

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
        return $this->getUrl().'ConfirmPayment';
    }
}