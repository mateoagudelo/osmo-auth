# Osmo Auth

**Authentication for PHP 8. Secure and easy.**

## Requirements
* PHP 8.0+ (`php 8`)
* PDO (PHP Data Objects) extension (`pdo`)
* MySQL 5.6+ **or** MariaDB 5.5.23+ **or** PostgreSQL 9.5.10+

## Installation
1. Include the library via Composer:

   ```
   $ composer require osmo/auth
   ```

1. Include the Composer autoloader:

   ```php
   require __DIR__ . '/vendor/autoload.php';
   ```

1. Set up a database and create the required tables:

## Usage
* [Connect to database](#connect-to-database)
* [Create and configure a new instance](#create-and-configure-a-new-instance)
* [Config login](#config-login)
* [Methods to verify passwords](#methods-to-verify-passwords)
* [Redirect on successful login](#redirect-on-successful-login)
* [Verify user login](#verify-user-login)
* [Clean user session](#clean-user-session)

### Connect to database

The three required data are the name of the database, the username and password. If your table is not named users, please specify it in array or use the setTable method.

```php
$con = new Osmo\Database([
    'username' => 'root',
    'database' => 'system',
    'password' => 'password'
  //'table'    => 'users_system' 
]);
//Default value {users}
//$con->setTable('users_table');
```

### Create and configure a new instance

To configure the instance, you must pass two parameters, **$connection** and an **array[]** with the two fields in the database to be validated.

```php
$auth = new Osmo\Auth($con, ['email', 'password']);
```

### Config login

In case the data is incorrect. The **make()** function will return a redirect to the same path where the **$_POST** method was executed.

```php
if ($auth->isPost()) {
    $auth->make($auth->inputEmailAddress(), $auth->input('password'), 'md5');
}
```

### Methods to verify passwords

* md5
* sha1
* crypt
* password_verify (default verification)

```php
$auth->make($auth->input('email'), $auth->input('password'), 'crypt');
```

To use the **password_verify()** method, leave the third parameter blank.

```php
$auth->make($auth->input('email'), $auth->input('password'));
```

### Redirect on successful login

To redirect user, you need pass the callback function:

```php
$auth->make($auth->inputEmailAddress(), $auth->input('password'), 'md5', function (){
    Osmo\Response::redirect('/');
});
```

### Verify user login

You can check if a user is logged in, with the following function:

```php
if(Osmo\SessionManager::auth()) {
    //
}
```

### Clean user session

To clean up the sessions, you need to run the following function:

```php
Osmo\SessionManager::destroy();
```
