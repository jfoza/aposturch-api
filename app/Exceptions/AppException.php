<?php

namespace App\Exceptions;

use Exception;

class AppException extends Exception
{
    private mixed $_options;

    public function __construct(
        $message,
        $code = 0,
        Exception $previous = null,
        $options = []
    ) {
        parent::__construct(json_encode($message), $code, $previous);

        $this->_options = $options;
    }

    public function getOptions()
    {
        return $this->_options;
    }
}

