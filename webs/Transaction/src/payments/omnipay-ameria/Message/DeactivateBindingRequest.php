<?php

namespace Omnipay\Ameria\Message;

/**
 * Class DeactivateBindingRequest
 * @package Omnipay\Ameria\Message
 */
class DeactivateBindingRequest extends BindingRequest
{
    /**
     * @return array|mixed
     * @throws \Omnipay\Common\Exception\InvalidRequestException
     */
    public function getData()
    {
        $this->validate('cardHolderID');

        return array_merge(parent::getData(), [
            'CardHolderID' => $this->getCardHolderId(),
        ]);
    }

    /**
     * @return string
     */
    public function getEndpoint()
    {
        return $this->getUrl().'DeactivateBinding';
    }
}