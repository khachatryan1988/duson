<?php

namespace Omnipay\Ameria\Message;

/**
 * Class InitPaymentRequest
 * @package Omnipay\Ameria\Message
 */
class InitPaymentRequest extends AbstractRequest
{
    /**
     * @return array|mixed|void
     * @throws \Omnipay\Common\Exception\InvalidRequestException
     */
    public function getData()
    {
        $this->validate('clientId', 'description', 'transactionId', 'amount', 'returnUrl');

        $data = parent::getData();

        //required attributes
        $data['ClientID'] = $this->getClientId();
        $data['Description'] = $this->getDescription();
        $data['OrderID'] = $this->getTransactionId();
        $data['Amount'] = $this->getAmount();
        $data['BackURL'] = $this->getReturnUrl();

        if ($this->getCurrency()) {
            $data['Currency'] = $this->currencyISOCodes[$this->getCurrency()];
        }

        if ($this->getOpaque()) {
            $data['Opaque'] = $this->getOpaque();
        }

        if ($this->getCardHolderId()) {
            $data['CardHolderID'] = $this->getCardHolderId();
        }

        return $data;
    }

    /**
     * @return string
     */
    public function getEndpoint()
    {
        return $this->getUrl().'InitPayment';
    }
}