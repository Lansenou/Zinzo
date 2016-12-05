<?php
/**
 * Created by PhpStorm.
 * User: Lansenou
 * Date: 8/12/2016
 * Time: 19:18
 */

namespace App\Bundle;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;

class User implements UserInterface
{
    private $app;
    private $username;
    private $email;
    private $plainPassword;
    private $password;
    private $roles;

    public function User($app) {
        $this->app = $app;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function setUsername($username)
    {
        $this->username = $username;
    }

    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    public function setPlainPassword($password)
    {
        $this->plainPassword = $password;
        $this->password = password_hash($this->plainPassword, PASSWORD_DEFAULT);
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }

    public function getRoles()
    {
        return $this->roles;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function eraseCredentials()
    {
    }

    /**
     * Returns the salt that was originally used to encode the password.
     *
     * This can return null if the password was not encoded using a salt.
     *
     * @return string|null The salt
     */
    public function getSalt()
    {
    }
}