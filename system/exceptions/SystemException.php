<?php


/**
 * Exception class to throw http error
 * for example: http response code 404
 */
class SystemException extends Exception{
    /**
     * @param int $statusCode http status code
     */
    public function __construct(int $statusCode)
    {
        parent::__construct("$statusCode", $statusCode);
    }
}