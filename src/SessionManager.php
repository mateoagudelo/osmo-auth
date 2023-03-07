<?php

namespace Osmo;

class SessionManager
{
    public static function verify()
    {
        if(session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
    }

    public static function make(array $row, $to_session, string $exception)
    {
        if(!is_array($row) || count($row) == 0) {
            throw new \Exception('The array is empty.');
        }

        self::verify();

        foreach ($row as $key => $item) {

            if(is_array($to_session) AND !is_null($to_session) AND count($to_session) > 0) {

                foreach ($to_session as $session) {

                    if((string)$key == (string)$session AND (string)$key != (string)$exception) {
                        $_SESSION[$key] = $item;
                    }

                }

            } else {

                if($key != $exception) {
                    $_SESSION[$key] = $item;
                }

            }

        }

        $_SESSION['hydra_authed'] = true;
    }

    public static function auth(): bool
    {
        self::verify();

        if(isset($_SESSION['hydra_authed'])) {
            return true;
        }

        return false;
    }

    public static function all(): array
    {
        self::verify();
        return $_SESSION;
    }

    public static function destroy(): bool
    {
        self::verify();
        return session_destroy();
    }
}