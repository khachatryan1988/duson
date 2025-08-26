<?php

namespace Omnipay\Ameria\Message;

use \Omnipay\Common\Message\AbstractRequest AS CommonAbstractRequest;

/**
 * Class AbstractRequest
 * @package Omnipay\Ameria\Message
 */
abstract class AbstractRequest extends CommonAbstractRequest
{
    /**
     * Endpoint URL'S for main payment system.
     * @var string URL
     */
    protected $endpoint = 'https://services.ameriabank.am/VPOS/api/VPOS/';
    protected $testEndpoint = 'https://servicestest.ameriabank.am/VPOS/api/VPOS/';

    /**
     * Endpoint URL'S for getting information about transactions
     * @var string
     */
    protected $adminEndpoint = 'https://payments.ameriabank.am/Admin/webservice/TransactionsInformationService.svc?wsdl';
    protected $adminTestEndpoint = 'https://testpayments.ameriabank.am/Admin/webservice/TransactionsInformationService.svc?wsdl';

    /**
     * Currency ISO codes.
     * @var array
     */
    protected $currencyISOCodes = [
        'AMD' => '051',
        'USD' => '840',
        'EUR' => '978',
        'RUB' => '643'
    ];

    /**
     * Amount for test
     * @var int
     */
    protected $testAmount = 10;

    /**
     * Currency for test
     * @var string
     */
    protected $testCurrency = 'AMD';

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
     * Set the request client ID.
     *
     * @param string $value
     *
     * @return $this
     */
    public function setClientId($value)
    {
        return $this->setParameter('clientId', $value);
    }

    /**
     * Get the request paymentId.
     * @return $this
     */
    public function getPaymentId()
    {
        return $this->getParameter('paymentId');
    }

    /**
     * Set the request paymentId.
     *
     * @param $value
     *
     * @return $this
     */
    public function setPaymentId($value)
    {
        return $this->setParameter('paymentId', $value);
    }

    /**
     * @return mixed
     */
    public function getOpaque()
    {
        return $this->getParameter('opaque');
    }

    /**
     * Set the request Opaque.
     *
     * @param string $value
     *
     * @return $this
     */
    public function setOpaque($value)
    {
        return $this->setParameter('opaque', $value);
    }

    /**
     * @return mixed
     */
    public function getCardHolderId()
    {
        return $this->getParameter('cardHolderID');
    }

    /**
     * Set the request CardHolderId.
     *
     * @param $value
     *
     * @return $this
     */
    public function setCardHolderId($value)
    {
        return $this->setParameter('cardHolderID', $value);
    }

    /**
     * @return mixed
     */
    public function getLanguage()
    {
        return $this->getParameter('language');
    }

    /**
     * Set the request language.
     *
     * @param string $value
     *
     * @return $this
     */
    public function setLanguage($value)
    {
        return $this->setParameter('language', $value);
    }

    /**
     * Get Amount
     * @return int|string
     * @throws \Omnipay\Common\Exception\InvalidRequestException
     */
    public function getAmount()
    {
        return $this->getTestMode() ? $this->testAmount : parent::getAmount();
    }

    /**
     * Get Currency
     * @return string
     */
    public function getCurrency()
    {
        return $this->getTestMode() ? $this->testCurrency : parent::getCurrency();
    }

    /**
     * Get endpoint url
     * @return mixed
     */
    abstract public function getEndpoint();

    /**
     * Get admin url depends on test mode.
     * @return string
     */
    public function getUrl()
    {
        $url = $this->getTestMode() ? $this->testEndpoint : $this->endpoint;
        return $url;
    }

    /**
     * Get admin url depends on test mode.
     * @return string
     */
    public function getAdminUrl()
    {
        return $this->getTestMode() ? $this->adminTestEndpoint : $this->adminEndpoint;
    }

    /**
     * Get HTTP Method.
     * This is nearly always POST but can be over-ridden in sub classes.
     * @return string
     */
    public function getHttpMethod()
    {
        return 'POST';
    }

    /**
     * @return array
     */
    public function getHeaders()
    {
        $headers = [];

        return $headers;
    }

    /**
     * {@inheritdoc}
     */
    public function sendData($data)
    {
        $headers = [
            'Content-Type' => 'application/x-www-form-urlencoded',
        ];

        $body = $data ? http_build_query($data, '', '&') : null;

        $httpResponse = $this->httpClient->request($this->getHttpMethod(), $this->getEndpoint(), $headers, $body);

        return $this->createResponse($httpResponse->getBody()->getContents(), $httpResponse->getHeaders());
    }

    /**
     * @param       $data
     * @param array $headers
     *
     * @return Response
     */
    protected function createResponse($data, $headers = [])
    {
        return $this->response = new Response($this, $data, $headers);
    }

    /**
     * Prepare data to send
     * @return array|mixed
     * @throws \Omnipay\Common\Exception\InvalidRequestException
     */
    public function getData()
    {
        $this->validate('username', 'password');

        return [
            'Username' => $this->getUsername(),
            'Password' => $this->getPassword(),
        ];
    }

    /**
     * Get Soap Client
     * @return \SoapClient
     * @throws \SoapFault
     */
    public function getSoapClient()
    {
        return new \SoapClient($this->getEndpoint(), [
            'soap_version'    => SOAP_1_1,
            'exceptions'      => true,
            'trace'           => 1,
            'wsdl_local_copy' => true
        ]);
    }
}
