<?php

namespace App\Controllers;

use Symfony\Component\HttpFoundation\JsonResponse,
    Symfony\Component\HttpFoundation\Request,
    App\Model\Service\ApiAuthServiceInterface;

class AuthController
{
    public function signInAction(ApiAuthServiceInterface $aas, Request $req)
    {
        $response = []; 

        try
        {
            $aas->signIn($req);
            $response['status'] = 'success';
            return new JsonResponse($response);
        }
        catch (\Exception $ex)
        {
            $response['status'] = 'error';
            $response['message'] = $ex->getMessage();
            return new JsonResponse($response, 500);
        }
    }

    public function getApiKeyAction(ApiAuthServiceInterface $aas, Request $req)
    {
        $response = []; 

        try
        {
            $response['api_key'] = $aas->getApiKey($req);
            $response['status'] = 'success';
            return new JsonResponse($response);
        }
        catch (\Exception $ex)
        {
            $response['status'] = 'error';
            $response['message'] = $ex->getMessage();
            return new JsonResponse($response, 500);
        }
    }
}