<?php
declare(strict_types = 1);

namespace App\Service;

use Aws\Result;
use App\Entity\AuthenticationResult;
use App\Security\AuthenticationProviderInterface;
use Aws\CognitoIdentityProvider\CognitoIdentityProviderClient;
use Aws\CognitoIdentityProvider\Exception\CognitoIdentityProviderException;

/**
 * AWS Cognito authentication provider
 *
 * @author Victor Cruz <cruzrosario@gmail.com>
 */
class CognitoAuthenticationProvider implements AuthenticationProviderInterface
{
    /**
     * @var string
     */
    private const RESET_REQUIRED = 'PasswordResetRequiredException';

    /**
     * @var string
     */
    private const USER_NOT_FOUND = 'UserNotFoundException';

    /**
     * @var string
     */
    private const INCORRECT_PASSWORD = 'NotAuthorizedException';

    /**
     * @var CognitoIdentityProviderClient
     */
    private $cognitoClient;

    /**
     * @var string
     */
    private $clientId;

    /**
     * @var string
     */
    private $clientSecret;

    /**
     * @var string
     */
    private $poolId;

    /**
     * AuthController constructor.
     *
     * @param CognitoIdentityProviderClient $cognitoClient
     * @param string                        $clientId
     * @param string                        $clientSecret
     * @param string                        $poolId
     */
    public function __construct(CognitoIdentityProviderClient $cognitoClient, string $clientId, string $clientSecret, string $poolId)
    {
        $this->cognitoClient = $cognitoClient;
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
        $this->poolId = $poolId;
    }

    /**
     * @inheritdoc
     */
    public function authenticate(string $email, string $password): ?AuthenticationResult
    {
        try
        {
            $response = $this->cognitoClient->adminInitiateAuth([
                'AuthFlow'       => 'ADMIN_NO_SRP_AUTH',
                'AuthParameters' => [
                    'USERNAME'     => $email,
                    'PASSWORD'     => $password,
                    'SECRET_HASH'  => $this->cognitoSecretHash($email)
                ],
                'ClientId'   => $this->clientId,
                'UserPoolId' => $this->poolId
            ]);

            return $this->parse($response);
        }
        catch (CognitoIdentityProviderException $exception)
        {
            if ($exception->getAwsErrorCode() === self::RESET_REQUIRED ||
                $exception->getAwsErrorCode() === self::INCORRECT_PASSWORD ||
                $exception->getAwsErrorCode() === self::USER_NOT_FOUND) {

                return null;
            }

            throw $exception;
        }
    }

    /**
     * Creates the Cognito secret hash
     * @param string $username
     * @return string
     */
    private function cognitoSecretHash($username)
    {
        return $this->hash($username . $this->clientId);
    }

    /**
     * Creates a HMAC from a string
     *
     * @param string $message
     * @return string
     */
    private function hash($message)
    {
        $hash = hash_hmac(
            'sha256',
            $message,
            $this->clientSecret,
            true
        );

        return base64_encode($hash);
    }

    /**
     * Parse AWS Result to domain result
     *
     * @param Result $result
     * @return AuthenticationResult
     */
    private function parse(Result $result): AuthenticationResult
    {
        $authenticationResult = $result->get('AuthenticationResult');

        return new AuthenticationResult(
            $authenticationResult['AccessToken'],
            (int)$authenticationResult['ExpiresIn'],
            $authenticationResult['TokenType'],
            $authenticationResult['RefreshToken'],
            $authenticationResult['IdToken']
        );
    }
}