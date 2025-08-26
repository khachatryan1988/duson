<?php

namespace Omnipay\Ameria\Message;

/**
 * Class MakeBindingPaymentRequest
 * @package Omnipay\Ameria\Message
 */
class MakeBindingPaymentRequest extends BindingRequest
{
    /**
     * @return array|mixed|void
     * @throws \Omnipay\Common\Exception\InvalidRequestException
     */
    public function getData()
    {
        $this->validate('description', 'transactionId', 'amount', 'returnUrl');

        $data = parent::getData();

        //required attributes
        $data['Description'] = $this->getDescription();
        $data['OrderID'] = $this->getTransactionId();
        $data['Amount'] = $this->getAmount();
        $data['BackURL'] = $this->getReturnUrl();
        $data['CardHolderID'] = $this->getCardHolderId();

        if ($this->getCurrency()) {
            $data['Currency'] = $this->currencyISOCodes[$this->getCurrency()];
        }

        if ($this->getOpaque()) {
            $data['Opaque'] = $this->getOpaque();
        }

        return $data;
    }

    /**
     * @return string
     */
    public function getEndpoint()
    {
        return $this->getUrl().'MakeBindingPayment';
    }
}