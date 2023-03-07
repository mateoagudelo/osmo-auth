<?php

namespace Osmo;

use Osmo\SessionManager;

class Auth
{
    private $connection;
    private Database $class;
    protected array $data = [];
    protected $to_session;
    public array $errors = [];

    public function __construct(mixed $con, array $data = [], array|null $to_session = null)
    {
        $this->connection = $con->start();
        $this->class = $con;
        $this->data = $data;
        $this->to_session = $to_session;
    }

    public function verify(string $password, string $hash, $method = null): bool
    {
        switch ($method) {
            case 'md5':

                if(md5($password) === $hash) {
                    return true;
                }

                break;

            case 'sha1':

                if(sha1($password) === $hash) {
                    return true;
                }

                break;

            case 'crypt':

                if(hash_equals($hash, crypt($password, $hash))) {
                    return true;
                }

                break;

            default:

                if(password_verify($password, $hash)) {
                    return true;
                }

                break;
        }

        return false;
    }

    public function make(string|null $field, string|null $password, $method = '', $callback = null)
    {
        $que = $this->connection->prepare('SELECT * FROM '.$this->class->getTable().' WHERE '.$this->data[0].' = :'.$this->data[0]);
        $que->bindParam(':'.$this->data[0], $field, \PDO::PARAM_STR);
        $que->execute();
        $row = $que->fetch(\PDO::FETCH_ASSOC);

        if($row) {

            //passed $password ($request) and hash $row
            if($this->verify($password, $row[$this->data[1]], $method)) {
                //Create sessions
                SessionManager::make($row, $this->to_session, $this->data[1]);
                return true;
            }

        }

        return header('Location: ' . $_SERVER['HTTP_REFERER']);
    }

    public function errors(): array
    {
        return $this->errors = [];
    }

    public function input($name = null, $default = null): mixed
    {
        if(!empty($name) AND !is_null($name) AND isset($_POST[$name])) {
            return $_POST[$name];
        }

        return $default;
    }

    public function inputEmailAddress(string|null $name = null)
    {
        if(!empty($name) AND !is_null($name) AND isset($_POST[$name])) {
            return htmlspecialchars(filter_var($_POST[$name], FILTER_SANITIZE_EMAIL), ENT_QUOTES);
        } else {
            foreach (['email', 'mail', 'email_address', 'correo', 'correo_electronico'] as $input) {
                if(isset($_POST[$input])) {
                    return htmlspecialchars(filter_var($_POST[$input], FILTER_SANITIZE_EMAIL), ENT_QUOTES);
                }
            }
        }
    }

    public function param($name = null, $default = null): mixed
    {
        if(!empty($name) AND !is_null($name) AND isset($_GET[$name])) {
            return $_GET[$name];
        }

        return $default;
    }

    public function isPost(): bool
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            return true;
        }

        return false;
    }

}