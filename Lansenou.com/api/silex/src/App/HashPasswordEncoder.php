<?php
/**
 * Created by PhpStorm.
 * User: Lansenou
 * Date: 8/3/2016
 * Time: 02:17
 */

namespace App;

use Symfony\Component\Security\Core\Encoder\BasePasswordEncoder;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;

/** http://php.net/manual/en/function.password-hash.php */
class HashPasswordEncoder extends BasePasswordEncoder
{
    private $algo;
    private $options;

    /**
     * http://php.net/manual/en/function.password-hash.php
     *
     * @param integer $encrytOptions A password algorithm constant denoting the algorithm to use when hashing the password.
     * @param array $options An associative array containing options. See the password algorithm constants for documentation on the supported options for each algorithm
     */
    public function __construct($encrytOptions = PASSWORD_DEFAULT, $options = null)
    {
        $this->$encrytOptions = $encrytOptions;
        $this->options = $options;
    }

    /**
     * {@inheritdoc}
     */
    public function encodePassword($raw, $salt)
    {
        if ($this->isPasswordTooLong($raw)) {
            throw new BadCredentialsException('Invalid password.');
        }

        return password_hash($raw, $this->algo, $this->options);
    }

    /**
     * {@inheritdoc}
     */
    public function isPasswordValid($encoded, $raw, $salt)
    {

        if ($this->isPasswordTooLong($raw)) {
            return false;
        }

        return password_verify($raw, $encoded);
    }
}