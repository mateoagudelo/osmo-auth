<?php

namespace Osmo\Http;

class Request
{
    public function input($name = null, $default = null): mixed
    {
        if(!empty($name) AND !is_null($name) AND isset($_POST[$name])) {
            return $_POST[$name];
        }

        if(!is_null($default)) {
            return $default;
        }

        return false;
    }

    public function query(string $name = null, string $default = null): mixed
    {
        if(!empty($name) AND !is_null($name) AND isset($_GET[$name])) {
            return $_GET[$name];
        }

        if(!is_null($default)) {
            return $default;
        }

        return false;
    }

    public function hasInput(string $name): bool
    {
        if($this->input($name)) {
            return true;
        }

        return false;
    }

    public function hasQuery(string $name): bool
    {
        if($this->query($name)) {
            return true;
        }

        return false;
    }

    public function inputEmailAddress(string|null $name = null): mixed
    {
        if(!empty($name) AND !is_null($name)) {
            return htmlspecialchars(filter_var($this->input($name), FILTER_SANITIZE_EMAIL), ENT_QUOTES);
        } else {
            foreach (['email', 'mail', 'email_address', 'correo', 'correo_electronico'] as $input) {
                if(!is_null($this->input($input))) {
                    return htmlspecialchars(filter_var($this->input($input), FILTER_SANITIZE_EMAIL), ENT_QUOTES);
                }
            }
        }
    }

    public function isPost(): bool
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            return true;
        }

        return false;
    }
}