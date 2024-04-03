<?php

namespace Modules\Auth\Abstracts;

use App\Exceptions\ValidationErrorsException;
use Exception;

abstract class AbstractAuthException extends Exception
{
    public static function createInstance(): static
    {
        return new static();
    }

    /**
     * @throws ValidationErrorsException
     */
    protected function throwValidationException(array $errors)
    {
        throw new ValidationErrorsException($errors);
    }
}
