<?php

namespace App\Model\Service;

use Symfony\Component\Security\Core\Encoder\BCryptPasswordEncoder;

class SaltLessPasswordEncoder implements SaltLessPasswordEncoderInterface
{
    const COMMON_SALT = 'saltsaltsaltsaltsaltsalt';

    private $encoder;

    public function __construct($cost)
    {
        // echo $a;
        $this->encoder = new BCryptPasswordEncoder($cost);
    }

    /**
     * Encodes the raw password.
     *
     * @param string $raw  The password to encode
     *
     * @return string The encoded password
     */
    public function encodePassword($raw)
    {
        return $this->encoder->encodePassword($raw, self::COMMON_SALT);
    }

    /**
     * Checks a raw password against an encoded password.
     *
     * @param string $encoded An encoded password
     * @param string $raw     A raw password
     *
     * @return Boolean true if the password is valid, false otherwise
     */
    public function isPasswordValid($encoded, $raw)
    {
        return $this->encoder->isPasswordValid($encoded, $raw, self::COMMON_SALT);
    }
}
