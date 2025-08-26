<?php

namespace Omnipay\Ameria\Message;

/**
 * Class PaymentDetailsRequest
 * @package Omnipay\Ameria\Message
 */
class PaymentDetailsRequest extends AbstractRequest
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
        return $this->getUrl().'GetPaymentDetails';
    }
}