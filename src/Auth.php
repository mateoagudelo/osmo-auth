<?php

namespace Osmo;

use Osmo\SessionManager;

class Auth
{
    private $connection;
    private Database $class;
    protected array $data = [];
    protected $to_session;

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

    public function make(string|null $field, string|null $password, $method = '', callable $callback = null): mixed
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

                if(is_callable($callback)) {
                    return $callback();
                }

                return true;
            }

        }

        return Osmo\Http\Response::back();
    }

}