<?php

namespace Omnipay\Ameria\Message;

/**
 * Class GetTransactionListRequest
 * @package Omnipay\Ameria\Message
 */
class GetTransactionListRequest extends AbstractRequest
{
    /**
     * Get the request start date.
     * @return $this
     */
    public function getStartDate()
    {
        return $this->getParameter('startDate');
    }

    /**
     * Set the request start date.
     * List of transactions from (format: yyyy/MM/dd HH:mm)
     *
     * @param $value
     *
     * @return $this
     */
    public function setStartDate($value)
    {
        return $this->setParameter('startDate', $value);
    }

    /**
     * Get the request end date.
     * @return $this
     */
    public function getEndDate()
    {
        return $this->getParameter('endDate');
    }

    /**
     * Set the request end date.
     * List of transactions from (format: yyyy/MM/dd HH:mm)
     *
     * @param $value
     *
     * @return $this
     */
    public function setEndDate($value)
    {
        return $this->setParameter('endDate', $value);
    }

    /**
     * @param mixed $data
     *
     * @return \Omnipay\Ameria\Message\Response|\Omnipay\Common\Message\ResponseInterface
     * @throws \SoapFault
     */
    public function sendData($data)
    {
        $response = $this->createSoapRequest($data);

        return $this->createResponse($response);
    }

    /**
     * @return array|mixed
     * @throws \Omnipay\Common\Exception\InvalidRequestException
     */
    public function getData()
    {
        $this->validate('clientId', 'startDate', 'endDate');

        return array_merge(parent::getData(), [
            'ClientID'  => $this->getClientId(),
            'StartDate' => $this->getStartDate(),
            'EndDate'   => $this->getEndDate()
        ]);
    }

    /**
     * @return string
     */
    public function getEndpoint()
    {
        return $this->getAdminUrl();
    }

    /**
     * Create Soap Request
     *
     * @param $data
     *
     * @return mixed
     * @throws \SoapFault
     */
    protected function createSoapRequest($data)
    {
        return $this->getSoapClient()->GetTransactionList($data);
    }
}