<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\HttpFoundation\JsonResponse;


class ApiTokenAuthenticator extends AbstractAuthenticator
{
    private string $apiToken = 'test-token-123';

    public function supports(Request $request): ?bool
    {
        return str_starts_with($request->getPathInfo(), '/api/');
    }

    public function authenticate(Request $request): SelfValidatingPassport
    {

        $authHeader = $request->headers->get('Authorization');
        file_put_contents('/tmp/auth_debug.txt', 'Auth header: ' . $authHeader . PHP_EOL, FILE_APPEND);


        if (!$authHeader || !str_starts_with($authHeader, 'Bearer ')) {
            throw new AuthenticationException('No API token provided');
        }

        $token = substr($authHeader, 7);

        if ($token !== $this->apiToken) {
            throw new AuthenticationException('Invalid API token');
        }

        return new SelfValidatingPassport(
            new UserBadge('api-user', fn() => new ApiUser('api-user'))
        );
    }

    public function onAuthenticationSuccess(Request $request, $token, string $firewallName): ?Response
    {
        return null; // laat request doorgaan
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        return new JsonResponse([
            'error' => 'Authentication failed',
            'message' => $exception->getMessage(),
        ], Response::HTTP_UNAUTHORIZED);
    }
}