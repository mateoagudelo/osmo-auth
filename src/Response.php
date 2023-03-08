<?php

namespace Osmo;

class Response
{
    public static function redirect(string|bool $path = null): mixed
    {
        if(!is_null($path) or !empty($path)) {
            return header('Location: '.$path);
        }

        return header('Location: /');
    }
}