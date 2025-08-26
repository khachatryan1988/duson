<?php

namespace Omnipay\Ameria\Message;

use Omnipay\Ameria\Constants\ResponseStatus;
use Omnipay\Ameria\Traits\ResponseDetail;
use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RedirectResponseInterface;
use Omnipay\Common\Message\RequestInterface;
use Omnipay\Ameria\Traits\OrderDetail;

/**
 * Ameria Response.
 * This is the response class for all Ameria requests.
 * @see \Omnipay\Ameria\Gateway
 */
class Response extends AbstractResponse implements RedirectResponseInterface
{
    use OrderDetail, ResponseDetail;

    /**
     * Gateway payment Url
     * @var string
     */
    protected $merchantUrl = 'https://services.ameriabank.am/VPOS/Payments/Pay';
    protected $merchantTestUrl = 'https://servicestest.ameriabank.am/VPOS/Payments/Pay';

    /**
     * @var array
     */
    protected $headers = [];

    public function __construct(RequestInterface $request, $data, $headers = [])
    {
        $this->request = $request;
        $this->data = json_decode($data, true);
        $this->headers = $headers;
    }

    /**
     * Is the transaction successful?
     * @return bool
     */
    public function isSuccessful()
    {
        if ($this->getOrderStatus()) {
            return $this->isDeposited() && $this->isCompleted();
        }

        return $this->isCompleted() ?: $this->isNotError();
    }

    /**
     * Is the payment canceled?
     * @return bool
     */
    public function isCancelled()
    {
        return $this->isVoid();
    }

    /**
     * Does the response require a redirect?
     * @return bool
     */
    public function isRedirect()
    {
        if ($this->isNotError() && !$this->getBindingID()) {
            return true;
        }

        return false;
    }

    /**
     * Get redirect URL
     * @return string
     */
    public function getRedirectUrl()
    {
        return $this->getMerchantUrl().'?'.http_build_query($this->getRedirectData());
    }

    /**
     * @return string
     */
    public function getMerchantUrl()
    {
        $url = $this->getRequest()->getTestMode() ? $this->merchantTestUrl : $this->merchantUrl;
        return $url;
    }

    /**
     * @return array
     */
    public function getRedirectData()
    {
        return [
            'id'   => $this->getPaymentId(),
            'lang' => $this->getRequest()->getLanguage(),
        ];
    }

    /**
     * Get the transaction reference.
     * @return string|null
     */
    public function getTransactionReference()
    {
        if (isset($this->data['OrderID'])) {
            return $this->data['OrderID'];
        }

        return null;
    }

    /**
     * Get the orderStatus.
     * @return integer|null
     */
    public function getOrderStatus()
    {
        if (isset($this->data['OrderStatus'])) {
            return $this->data['OrderStatus'];
        }

        return null;
    }

    /**
     * Get the PaymentID reference.
     * @return mixed
     */
    public function getPaymentId()
    {
        if (isset($this->data['PaymentID'])) {
            return $this->data['PaymentID'];
        }

        return null;
    }

    /**
     * Get the PaymentType reference.
     * @return mixed
     */
    public function getPaymentType()
    {
        if (isset($this->data['PaymentType'])) {
            return $this->data['PaymentType'];
        }

        return null;
    }

    /**
     * Get the BindingID reference.
     * @return mixed
     */
    public function getBindingID()
    {
        if (isset($this->data['BindingID'])) {
            return $this->data['BindingID'];
        }

        return null;
    }

    /**
     * Get the CardHolderID
     * @return mixed
     */
    public function getCardHolderID()
    {
        if (isset($this->data['CardHolderID'])) {
            return $this->data['CardHolderID'];
        }

        return null;
    }

    /**
     * Get the error message
     * Returns null if the request was successful.
     * @return string|null
     */
    public function getMessage()
    {
        if (isset($this->data['ResponseMessage'])) {
            return $this->data['ResponseMessage'];
        }

        return null;
    }

    /**
     * Get the error code from the response.
     * Returns null if the request was successful.
     * @return string|null
     */
    public function getCode()
    {
        if (isset($this->data['ResponseCode'])) {
            return $this->data['ResponseCode'];
        }

        return null;
    }

    public function getData()
    {
        return $this->data;
    }
}
