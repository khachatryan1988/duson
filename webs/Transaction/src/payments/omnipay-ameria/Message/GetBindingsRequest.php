<?php

namespace Omnipay\Ameria\Message;

/**
 * Class GetBindingsRequest
 * @package Omnipay\Ameria\Message
 */
class GetBindingsRequest extends BindingRequest
{
    /**
     * @return string
     */
    public function getEndpoint()
    {
        return $this->getUrl().'GetBindings';
    }
}