<?php
declare(strict_types = 1);

namespace App\Security;

use App\Entity\AuthenticationResult;

/**
 * Common interface to implement different authentication providers
 *
 * @author Victor Cruz <cruzrosario@gmail.com>
 */
interface AuthenticationProviderInterface
{
    /**
     * Authenticate user
     *
     * @param string $email
     * @param string $password
     * @return AuthenticationResult
     */
    public function authenticate(string $email, string $password): ?AuthenticationResult;
}