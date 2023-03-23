<?php

namespace Osmo\Http;

class Response
{
    public static function redirect(string|bool $path = null): mixed
    {
        if(!is_null($path) or !empty($path)) {
            return header('Location: '.$path);
        }

        return header('Location: /');
    }

    public static function back(): mixed
    {
        return header('Location: ' . $_SERVER['HTTP_REFERER']);
    }
}