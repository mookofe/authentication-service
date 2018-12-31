<?php
declare(strict_types = 1);

namespace App\Entity;

/**
 * Class AuthenticationResult
 *
 * @author Victor Cruz <cruzrosario@gmail.com>
 */
final class AuthenticationResult
{
    /**
     * @var string
     */
    private $accessToken;

    /**
     * @var int
     */
    private $expiresIn;

    /**
     * @var string
     */
    private $tokenType;

    /**
     * @var string
     */
    private $refreshToken;

    /**
     * @var string
     */
    private $idToken;

    /**
     * AuthenticationResult constructor.
     *
     * @param string $accessToken
     * @param int $expiresIn
     * @param string $tokenType
     * @param string $refreshToken
     * @param string $idToken
     */
    public function __construct(string $accessToken, int $expiresIn, string $tokenType, string $refreshToken, string $idToken)
    {
        $this->accessToken = $accessToken;
        $this->expiresIn = $expiresIn;
        $this->tokenType = $tokenType;
        $this->refreshToken = $refreshToken;
        $this->idToken = $idToken;
    }
}