<?php

namespace Osmo;

class Validation
{
    /**
     * @throws \Exception
     */
    public static function required(array $params = []): void
    {
        foreach ($params as $param) {
            if(is_null($param) || empty($param)) {
                throw new \Exception('All parameters are required.');
            }
        }
    }

    /**
     * @throws \Exception
     */
    public static function email(string $email): void
    {
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new \Exception('The parameter must be a valid email.');
        }
    }

    /**
     * @throws \Exception
     */
    public static function number(int $number): void
    {
        if(!filter_var($number, FILTER_VALIDATE_INT)) {
            throw new \Exception('The parameter must be a valid number.');
        }
    }
}