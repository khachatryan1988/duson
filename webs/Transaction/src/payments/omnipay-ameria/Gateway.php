<?php

namespace Omnipay\Ameria;

use Omnipay\Ameria\Message\ActivateBindingRequest;
use Omnipay\Ameria\Message\CancelPaymentRequest;
use Omnipay\Ameria\Message\ConfirmPaymentRequest;
use Omnipay\Ameria\Message\DeactivateBindingRequest;
use Omnipay\Ameria\Message\GetBindingsRequest;
use Omnipay\Ameria\Message\GetProblemTransactionsRequest;
use Omnipay\Ameria\Message\GetTransactionListRequest;
use Omnipay\Ameria\Message\InitPaymentRequest;
use Omnipay\Ameria\Message\MakeBindingPaymentRequest;
use Omnipay\Ameria\Message\PaymentDetailsRequest;
use Omnipay\Ameria\Message\RefundPaymentRequest;

use Omnipay\Common\AbstractGateway;

/**
 * Class Gateway
 * @package Omnipay\Ameria
 * @method \Omnipay\Common\Message\RequestInterface authorize(array $options = array())
 * @method \Omnipay\Common\Message\RequestInterface completeAuthorize(array $options = array())
 * @method \Omnipay\Common\Message\RequestInterface capture(array $options = array())
 * @method \Omnipay\Common\Message\RequestInterface void(array $options = array())
 * @method \Omnipay\Common\Message\RequestInterface createCard(array $options = array())
 * @method \Omnipay\Common\Message\RequestInterface updateCard(array $options = array())
 * @method \Omnipay\Common\Message\RequestInterface deleteCard(array $options = array())
 */
class Gateway extends AbstractGateway
{
    /**
     * @return string
     */
    public function getName()
    {
        return 'Ameria';
    }

    /**
     * @return array
     */
    public function getDefaultParameters()
    {
        return [
            'clientId' => '',
            'username' => '',
            'password' => '',
        ];
    }

    /**
     * @return mixed
     */
    public function getTestMode()
    {
        return $this->getParameter('testMode');
    }

    /**
     * Set account login.
     *
     * @param $value
     *
     * @return $this
     */
    public function setTestMode($value)
    {
        return $this->setParameter('testMode', $value);
    }

    /**
     * @return mixed
     */
    public function getUsername()
    {
        return $this->getParameter('username');
    }

    /**
     * Set account login.
     *
     * @param $value
     *
     * @return $this
     */
    public function setUsername($value)
    {
        return $this->setParameter('username', $value);
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->getParameter('password');
    }

    /**
     * Set account password.
     *
     * @param $value
     *
     * @return $this
     */
    public function setPassword($value)
    {
        return $this->setParameter('password', $value);
    }

    /**
     * @return mixed
     */
    public function getClientId()
    {
        return $this->getParameter('clientId');
    }

    /**
     * Set amount.
     *
     * @param $value
     *
     * @return $this
     */
    public function setAmount($value)
    {
        return $this->setParameter('amount', $value);
    }

    /**
     * @return mixed
     */
    public function getAmount()
    {
        return $this->getParameter('amount');
    }

    /**
     * Set description.
     *
     * @param $value
     *
     * @return $this
     */
    public function setDescription($value)
    {
        return $this->setParameter('description', $value);
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->getParameter('description');
    }

    /**
     * Set transaction ID.
     *
     * @param $value
     *
     * @return $this
     */
    public function setTransactionId($value)
    {
        return $this->setParameter('transactionId', $value);
    }

    /**
     * @return mixed
     */
    public function getTransactionId()
    {
        return $this->getParameter('transactionId');
    }

    /**
     * Set language.
     *
     * @param $value
     *
     * @return $this
     */
    public function setLanguage($value)
    {
        return $this->setParameter('language', $value);
    }

    /**
     * @return mixed
     */
    public function getLanguage()
    {
        return $this->getParameter('language');
    }

    /**
     * Set return URL.
     *
     * @param $value
     *
     * @return $this
     */
    public function setReturnUrl($value)
    {
        return $this->setParameter('returnUrl', $value);
    }

    /**
     * @return mixed
     */
    public function getReturnUrl()
    {
        return $this->getParameter('returnUrl');
    }

    /**
     * Set account password.
     *
     * @param $value
     *
     * @return $this
     */
    public function setClientId($value)
    {
        return $this->setParameter('clientId', $value);
    }

    /**
     * Sets the request binding purchase.
     *
     * @param  $value
     *
     * @return $this
     */
    public function setBindingPayment($value)
    {
        return $this->setParameter('bindingPayment', $value);
    }

    /**
     * Sets the request callback data.
     *
     * @param  $value
     *
     * @return $this
     */
    public function setCallbackData()
    {
        $request = request()->request;
        return $this->setParameter('paymentId', $request->get('paymentID'));
    }

    /**
     * Get the request binding purchase.
     * @return $this
     */
    public function getBindingPayment()
    {
        return $this->getParameter('bindingPayment');
    }

    /**
     * Create Purchase Request depends on payment type.
     *
     * @param array $parameters
     *
     * @return \Omnipay\Common\Message\AbstractRequest
     */
    public function purchase(array $parameters = array())
    {
        return $this->getBindingPayment() ? $this->makeBindingPayment($parameters) : $this->initPayment($parameters);
    }

    /**
     * Create CompletePurchase Request.
     *
     * @param array $parameters
     *
     * @return \Omnipay\Common\Message\AbstractRequest
     */
    public function completePurchase(array $parameters = array())
    {
        return $this->createRequest(PaymentDetailsRequest::class, $parameters);
    }

    /**
     * Create CompletePurchase Request.
     *
     * @param array $parameters
     *
     * @return \Omnipay\Common\Message\AbstractRequest
     */
    public function getOrderStatus(array $parameters = array())
    {
        return $this->createRequest(PaymentDetailsRequest::class, $parameters);
    }

    /**
     * Create Refund Request.
     *
     * @param array $parameters
     *
     * @return \Omnipay\Common\Message\AbstractRequest
     */
    public function refund(array $parameters = array())
    {
        return $this->createRequest(RefundPaymentRequest::class, $parameters);
    }

    /**
     * Create InitPayment Request.
     *
     * @param array $parameters
     *
     * @return \Omnipay\Common\Message\AbstractRequest
     */
    public function initPayment(array $parameters = array())
    {
        return $this->createRequest(InitPaymentRequest::class, $parameters);
    }

    /**
     * Create MakeBindingPayment Request.
     *
     * @param array $parameters
     *
     * @return \Omnipay\Common\Message\AbstractRequest
     */
    public function makeBindingPayment(array $parameters = array())
    {
        return $this->createRequest(MakeBindingPaymentRequest::class, $parameters);
    }

    /**
     * Create ActivateBinding Request.
     *
     * @param array $parameters
     *
     * @return \Omnipay\Common\Message\AbstractRequest
     */
    public function activateBinding(array $parameters = array())
    {
        return $this->createRequest(ActivateBindingRequest::class, $parameters);
    }

    /**
     * Create DeactivateBinding Request.
     *
     * @param array $parameters
     *
     * @return \Omnipay\Common\Message\AbstractRequest
     */
    public function deactivateBinding(array $parameters = array())
    {
        return $this->createRequest(DeactivateBindingRequest::class, $parameters);
    }

    /**
     * Create GetBindings Request.
     *
     * @param array $parameters
     *
     * @return \Omnipay\Common\Message\AbstractRequest
     */
    public function getBindings(array $parameters = array())
    {
        return $this->createRequest(GetBindingsRequest::class, $parameters);
    }

    /**
     * Create ConfirmPayment Request.
     *
     * @param array $parameters
     *
     * @return \Omnipay\Common\Message\AbstractRequest
     */
    public function confirmPayment(array $parameters = array())
    {
        return $this->createRequest(ConfirmPaymentRequest::class, $parameters);
    }

    /**
     * Create CancelPayment Request.
     *
     * @param array $parameters
     *
     * @return \Omnipay\Common\Message\AbstractRequest
     */
    public function cancelPayment(array $parameters = array())
    {
        return $this->createRequest(CancelPaymentRequest::class, $parameters);
    }

    /**
     * Create getTransactionList Request.
     *
     * @param array $parameters
     *
     * @return \Omnipay\Common\Message\AbstractRequest
     */
    public function getTransactionList(array $parameters = array())
    {
        return $this->createRequest(GetTransactionListRequest::class, $parameters);
    }

    /**
     * Create GetProblemTransactions Request.
     *
     * @param array $parameters
     *
     * @return \Omnipay\Common\Message\AbstractRequest
     */
    public function getProblemTransactions(array $parameters = array())
    {
        return $this->createRequest(GetProblemTransactionsRequest::class, $parameters);
    }

    /**
     * Create getPaymentDetails Request.
     *
     * @param array $parameters
     *
     * @return \Omnipay\Common\Message\AbstractRequest
     */
    public function getPaymentDetails(array $parameters = array())
    {
        return $this->createRequest(PaymentDetailsRequest::class, $parameters);
    }

    public function __call($name, $arguments)
    {
        // TODO: Implement @method \Omnipay\Common\Message\RequestInterface authorize(array $options = array())
        // TODO: Implement @method \Omnipay\Common\Message\RequestInterface completeAuthorize(array $options = array())
        // TODO: Implement @method \Omnipay\Common\Message\RequestInterface capture(array $options = array())
        // TODO: Implement @method \Omnipay\Common\Message\RequestInterface void(array $options = array())
        // TODO: Implement @method \Omnipay\Common\Message\RequestInterface createCard(array $options = array())
        // TODO: Implement @method \Omnipay\Common\Message\RequestInterface updateCard(array $options = array())
        // TODO: Implement @method \Omnipay\Common\Message\RequestInterface deleteCard(array $options = array())
    }
}
