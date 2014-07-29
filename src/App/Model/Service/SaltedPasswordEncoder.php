<?php

namespace App\Model\Service;

use Symfony\Component\Security\Core\Encoder\BCryptPasswordEncoder;

class SaltedPasswordEncoder extends BCryptPasswordEncoder implements SaltedPasswordEncoderInterface
{
    
}
