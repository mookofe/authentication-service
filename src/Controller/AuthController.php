<?php
declare(strict_types = 1);

namespace App\Controller;

use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\FOSRestController;
use App\Security\AuthenticationProviderInterface;
use FOS\RestBundle\Controller\Annotations as Rest;

/**
 * AWS Cognito authentication provider
 *
 * @author Victor Cruz <cruzrosario@gmail.com>
 */
class AuthController extends FOSRestController
{
    /**
     * @var AuthenticationProviderInterface
     */
    private $authenticationProvider;

    /**
     * AuthController constructor.
     * @param AuthenticationProviderInterface $authenticationProvider
     */
    public function __construct(AuthenticationProviderInterface $authenticationProvider)
    {
        $this->authenticationProvider = $authenticationProvider;
    }

    /**
     * @Rest\Post("/login")
     *
     * @param Request $request
     * @return View
     */
    public function authenticate(Request $request): View
    {
        $email = $request->get('email');
        $password = $request->get('password');

        $result = $this->authenticationProvider->authenticate($email, $password);

        if ($result !== null){
            return new View($result);
        }

        return $this->invalidLoginView();
    }

    /**
     * Invalid login view
     *
     * @return View
     */
    private function invalidLoginView(): View
    {
        $data  = [
            'error' => 'Invalid email and password'
        ];

        return new View(
            $data,
            Response::HTTP_BAD_REQUEST
        );
    }
}