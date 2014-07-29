<?php

namespace App\Model\Service;

use Symfony\Component\HttpFoundation\Request;

interface ApiAuthServiceInterface
{
    public function signIn(Request $req);
    public function getApiKey(Request $req);
}