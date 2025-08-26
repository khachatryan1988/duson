<?php

namespace Omnipay\Ineco;

use Omnipay\Common\AbstractGateway;


/**
 * Ineco Gateway
 *
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
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return 'Ineco';
    }


    /**
     * @return array
     */
    public function getDefaultParameters()
    {
        return [
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
    public function getUserName()
    {
        return $this->getParameter('username');
    }

    /**
     * Set account login.
     *
     * @param $value
     * @return $this
     */
    public function setUserName($value)
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
     * @return $this
     */
    public function setPassword($value)
    {
        return $this->setParameter('password', $value);
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
     * Create Purchase Request.
     *
     * @param  array $parameters
     * @return \Omnipay\Common\Message\AbstractRequest
     */
    public function purchase(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Ineco\Message\RegisterRequest', $parameters);
    }

    /**
     * Sets the request callback data.
     *
     * @param  $value
     *
     * @return $this
     */
    public function setCallbackData($request)
    {
        return $this->setTransactionId($request->get('paymentID'));
    }

    /**
     * Create RegisterPreAuth Request.
     *
     * @param  array $parameters
     * @return \Omnipay\Common\Message\AbstractRequest
     */
    public function registerPreAuth(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Ineco\Message\RegisterPreAuthRequest', $parameters);
    }
    /**
     * Complete purchase
     *
     * @param array $options
     *
     * @return \Omnipay\Common\Message\AbstractRequest|\Omnipay\Common\Message\RequestInterface
     */
    public function completePurchase(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Ineco\Message\GetOrderStatusRequest', $parameters);
    }
    /**
     * Create GetOrderStatus Request.
     *
     * @param  array $parameters
     * @return \Omnipay\Common\Message\AbstractRequest
     */
    public function getOrderStatus(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Ineco\Message\GetOrderStatusRequest', $parameters);
    }

    /**
     * Create getOrderStatusExtended Request.
     *
     * @param  array $parameters
     * @return \Omnipay\Common\Message\AbstractRequest
     */
    public function getOrderStatusExtended(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Ineco\Message\GetOrderStatusExtendedRequest', $parameters);
    }

    /**
     * Create verifyEnrollment Request.
     *
     * @param  array $parameters
     * @return \Omnipay\Common\Message\AbstractRequest
     */
    public function verifyEnrollment(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Ineco\Message\VerifyEnrollmentRequest', $parameters);
    }

    /**
     * Create Deposit Request.
     *
     * @param array $parameters
     * @return \Omnipay\Common\Message\AbstractRequest
     */
    public function deposit(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Ineco\Message\DepositRequest', $parameters);
    }

    /**
     * Create Reverse Request.
     *
     * @param array $parameters
     * @return \Omnipay\Common\Message\AbstractRequest
     */
    public function reverse(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Ineco\Message\ReverseRequest', $parameters);
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
        return $this->createRequest('\Omnipay\Ineco\Message\RefundRequest', $parameters);
    }
}
