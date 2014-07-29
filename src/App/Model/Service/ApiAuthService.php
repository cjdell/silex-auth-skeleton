<?php

namespace App\Model\Service;

use App\Application,
    Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken,
    Symfony\Component\Security\Http\Event\InteractiveLoginEvent,
    Symfony\Component\Security\Core\SecurityContext,
    Symfony\Component\EventDispatcher\EventDispatcher,
    Symfony\Component\Security\Http\SecurityEvents,
    Symfony\Component\HttpFoundation\Request,
    Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;

class ApiAuthService implements ApiAuthServiceInterface
{
    private $app;
    private $apiKeyUserProvider;
    private $securityContext;
    private $eventDispatcher;
    private $saltLessPasswordEncoder;
    private $passwordEncoder;

    public function __construct(
      Application $app, 
      ApiKeyUserProviderInterface $apiKeyUserProvider, 
      SecurityContext $securityContext, 
      EventDispatcher $eventDispatcher, 
      SaltLessPasswordEncoderInterface $saltLessPasswordEncoder,
      SaltedPasswordEncoderInterface $passwordEncoder
    )
    {
        $this->app = $app;
        $this->apiKeyUserProvider = $apiKeyUserProvider;
        $this->securityContext = $securityContext;
        $this->eventDispatcher = $eventDispatcher;
        $this->saltLessPasswordEncoder = $saltLessPasswordEncoder;
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * Not actually using this method. Uses the the standard Symfony cookie based authentication
     *
     * @param Request $request The HTTP request
     */
    public function signIn(Request $request)
    {
        $username = $request->data['username'];
        $password = $request->data['password'];

        $user = $this->apiKeyUserProvider->loadUserByUsername($username);

        if ($user)
        {
            $passwordEncoder = $this->getFormPasswordEncoder();
            $correctPassword = $passwordEncoder->isPasswordValid($user->getPassword(), $password, $user->getSalt());

            if ($correctPassword)
            {
                $token = new UsernamePasswordToken($user, $user->getPassword(), "default", $user->getRoles());
                $this->securityContext->setToken($token);

                $loginEvent = new InteractiveLoginEvent($request, $token);
                $this->eventDispatcher->dispatch(SecurityEvents::INTERACTIVE_LOGIN, $loginEvent);
            }
            else
            {
                // Password bad
                throw new \Exception('Bad password');
            }
        }
        else
        {
            // Bad username
            throw new \Exception('Bad username');
        }
    }

    /**
     * Validate the user's credentials and generates a temporary API key for use only by that user
     *
     * @param Request $request The HTTP request
     */
    public function getApiKey(Request $request)
    {
        $username = $request->data['username'];
        $password = $request->data['password'];

        $user = $this->apiKeyUserProvider->loadUserByUsername($username);

        if ($user)
        {
            $passwordEncoder = $this->getFormPasswordEncoder();
            $correctPassword = $passwordEncoder->isPasswordValid($user->getPassword(), $password, $user->getSalt());

            if ($correctPassword)
            {
                $newApiKey = $this->getRandomApiKey();

                $apiKeyEncoded = $this->saltLessPasswordEncoder->encodePassword($newApiKey);
                $this->apiKeyUserProvider->setApiKey($user->getId(), $apiKeyEncoded);

                return $newApiKey;  // Return new random API key that isn't stored

                // return $this->saltLessPasswordEncoder->encodePassword($password);
            }
            else
            {
                // Password bad
                throw new \Exception('Bad password');
            }
        }
        else
        {
            // Bad username
            throw new \Exception('Bad username');
        }
    }

    private function getFormPasswordEncoder()
    {
        //return $this->app['security.encoder.digest'];
        return $this->passwordEncoder;
    }

    private function getRandomApiKey()
    {
        return md5(uniqid());
    }
}