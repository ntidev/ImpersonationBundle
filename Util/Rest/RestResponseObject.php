<?php

namespace GL\PartnerPortalBundle\Util\Rest;

use JMS\Serializer\Annotation as JMS;

class RestResponseObject {

    /**
     * @var boolean
     * @JMS\Type("boolean")
     * @JMS\SerializedName("has_error")
     */
    private $hasError;

    /**
     * @var array
     * @JMS\Type("array")
     * @JMS\SerializedName("additional_errors")
     */
    private $additionalErrors;

    /**
     * @var int
     * @JMS\Type("integer")
     */
    private $code;

    /**
     * @var string
     * @JMS\Type("string")
     */
    private $message;

    /**
     * @var array
     * @JMS\Type("array")
     */
    private $data;

    /**
     * @var string
     * @JMS\Type("string")
     */
    private $redirect;

    // DataTable properties

    /**
     * @var int
     * @JMS\Type("integer")
     */
    private $draw;

    /**
     * @var int
     * @JMS\Type("integer")
     */
    private $recordsFiltered;

    /**
     * @var int
     * @JMS\Type("integer")
     */
    private $recordsTotal;

    /**
     * @var array
     * @JMS\Type("array")
     */
    private $pagination;


    /**
     * RestResponseObject constructor.
     */
    public function __construct()
    {
        $this->additionalErrors = array();
    }


    /**
     * @return mixed
     */
    public function getHasError()
    {
        return $this->hasError;
    }

    /**
     * @param mixed $hasError
     * @return RestResponseObject
     */
    public function setHasError($hasError)
    {
        $this->hasError = $hasError;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAdditionalErrors()
    {
        return $this->additionalErrors;
    }

    /**
     * @param mixed $additionalErrors
     * @return RestResponseObject
     */
    public function setAdditionalErrors($additionalErrors)
    {
        $this->additionalErrors = $additionalErrors;
        return $this;
    }

    /**
     * @param mixed $additionalError
     * @return RestResponseObject
     */
    public function addAdditionalError($additionalError)
    {
        $this->additionalErrors[] = $additionalError;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param mixed $code
     * @return RestResponseObject
     */
    public function setCode($code)
    {
        $this->code = $code;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param mixed $message
     * @return RestResponseObject
     */
    public function setMessage($message)
    {
        $this->message = $message;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param mixed $data
     * @return RestResponseObject
     */
    public function setData($data)
    {
        $this->data = $data;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getRedirect()
    {
        return $this->redirect;
    }

    /**
     * @param mixed $redirect
     * @return RestResponseObject
     */
    public function setRedirect($redirect)
    {
        $this->redirect = $redirect;
        return $this;
    }

    /**
     * @return int
     */
    public function getDraw()
    {
        return $this->draw;
    }

    /**
     * @param int $draw
     * @return RestResponseObject
     */
    public function setDraw($draw)
    {
        $this->draw = $draw;
        return $this;
    }

    /**
     * @return int
     */
    public function getRecordsFiltered()
    {
        return $this->recordsFiltered;
    }

    /**
     * @param int $recordsFiltered
     * @return RestResponseObject
     */
    public function setRecordsFiltered($recordsFiltered)
    {
        $this->recordsFiltered = $recordsFiltered;
        return $this;
    }

    /**
     * @return int
     */
    public function getRecordsTotal()
    {
        return $this->recordsTotal;
    }

    /**
     * @param int $recordsTotal
     * @return RestResponseObject
     */
    public function setRecordsTotal($recordsTotal)
    {
        $this->recordsTotal = $recordsTotal;
        return $this;
    }

    /**
     * @return array
     */
    public function getPagination()
    {
        return $this->pagination;
    }

    /**
     * @param array $pagination
     * @return RestResponseObject
     */
    public function setPagination($pagination)
    {
        $this->pagination = $pagination;
        return $this;
    }



}