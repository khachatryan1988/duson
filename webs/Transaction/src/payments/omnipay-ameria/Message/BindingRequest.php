<?php

namespace Omnipay\Ameria\Message;

/**
 * Class BindingRequest
 * this class for all binding requests
 * @package Omnipay\Ameria\Message
 */
class BindingRequest extends AbstractRequest
{
    /**
     * Payment Types
     * @var array
     */
    protected $paymentTypes = [
        'MainRest' => 5,
        'Binding'  => 6,
        'PayPal'   => 7,
    ];

    /**
     * Get the request paymentType.
     * @return $this
     */
    public function getPaymentType()
    {
        return $this->getParameter('paymentType');
    }

    /**
     * Set the request paymentType.
     * 5-MainRest (arca) or 7-PayPal or 6-Binding
     *
     * @param $value
     *
     * @return $this
     */
    public function setPaymentType($value)
    {
        return $this->setParameter('paymentType', $value);
    }

    /**
     * @return array|mixed
     * @throws \Omnipay\Common\Exception\InvalidRequestException
     */
    public function getData()
    {
        $this->validate('clientId');

        return array_merge(parent::getData(), [
            'ClientID'    => $this->getClientId(),
            'PaymentType' => $this->getPaymentType() ?: $this->paymentTypes['Binding'],
        ]);
    }

    /**
     * @inheritDoc
     */
    public function getEndpoint()
    {
        return $this->getUrl();
    }
}