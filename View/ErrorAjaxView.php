<?php

namespace Jagilpe\AjaxModalsBundle\View;

/**
 * Defines a view to use as a response in an Ajax Controller
 *
 * @author Javier Gil Pereda <javier.gil@module-7.com>
 *
 */
class ErrorAjaxView implements AjaxViewInterface
{
    /**
     * @var string
     */
    protected $error = 1;

    /**
     *
     * @var integer
     */
    protected $errorCode = '';

    /**
     *
     * {@inheritDoc}
     * @see \Jagilpe\AjaxModalsBundle\View\AjaxViewInterface::getResponse()
     */
    public function getResponse()
    {
        $response = array(
            'type' => AjaxViewInterface::TYPE_ERROR,
            'response' => $this->getErrorCode(),
            'error' => $this->getError(),
        );

        return $response;
    }

    /**
     * Returns the error code
     *
     * @return integer
     */
    public function getErrorCode()
    {
        return $this->errorCode;
    }

    /**
     * Sets the error code
     *
     * @param integer $errorCode
     */
    public function setErrorCode($errorCode)
    {
        $this->errorCode = $errorCode;
    }

    /**
     * Returns the error description
     *
     * @return string
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * Sets the error description
     *
     * @param string $error
     */
    public function setError($error)
    {
        $this->error = $error;
    }

    /**
     * Sets the error description from an Exception
     *
     * @param \Exception $error
     */
    public function setErrorFromException(\Exception $exception)
    {
        $this->error = $exception->__toString();
    }
}