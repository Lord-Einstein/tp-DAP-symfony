<?php

namespace App\OpenApi;

use ApiPlatform\OpenApi\Factory\OpenApiFactoryInterface;
use ApiPlatform\OpenApi\Model\Operation;
use ApiPlatform\OpenApi\Model\PathItem;
use ApiPlatform\OpenApi\Model\RequestBody;
use ApiPlatform\OpenApi\OpenApi;
use Symfony\Component\DependencyInjection\Attribute\AsDecorator;
use Symfony\Component\DependencyInjection\Attribute\AutowireDecorated;

#[AsDecorator('api_platform.openapi.factory', priority: -1)]
final class OpenApiFactory implements OpenApiFactoryInterface
{
    public function __construct(
        #[AutowireDecorated] private readonly OpenApiFactoryInterface $decorated,
    ) {
    }

    public function __invoke(array $context = []): OpenApi
    {
        $openApi = ($this->decorated)($context);
        $paths = $openApi->getPaths();

        $schemas = $openApi->getComponents()->getSecuritySchemes();
        unset($schemas['JWT']);

        $paths->addPath('/api/auth/login', new PathItem(post: new Operation(
            operationId: 'login',
            tags: ['Authentification'],
            summary: 'Se connecter',
            security: [],
            requestBody: new RequestBody(
                required: true,
                content: new \ArrayObject([
                    'application/json' => ['schema' => [
                        'type' => 'object',
                        'required' => ['email', 'password'],
                        'properties' => [
                            'email' => ['type' => 'string', 'format' => 'email'],
                            'password' => ['type' => 'string', 'format' => 'password'],
                        ],
                    ]],
                ]),
            ),
            responses: [
                '200' => [
                    'description' => 'Jetons délivrés.',
                    'content' => new \ArrayObject([
                        'application/json' => ['schema' => [
                            'type' => 'object',
                            'required' => ['accessToken', 'refreshToken'],
                            'properties' => [
                                'accessToken' => ['type' => 'string'],
                                'refreshToken' => ['type' => 'string'],
                            ],
                        ]],
                    ]),
                ],
                '401' => ['description' => 'Identifiants invalides.'],
            ],
        )));

        $paths->addPath('/api/auth/refresh', new PathItem(post: new Operation(
            operationId: 'refresh',
            tags: ['Authentification'],
            summary: 'Renouveler le jeton d\'accès',
            requestBody: new RequestBody(
                required: true,
                content: new \ArrayObject([
                    'application/json' => ['schema' => [
                        'type' => 'object',
                        'required' => ['refreshToken'],
                        'properties' => [
                            'refreshToken' => ['type' => 'string'],
                        ],
                    ]],
                ]),
            ),
            responses: [
                '200' => [
                    'description' => "Jetons d'accès renouvelés.",
                    'content' => new \ArrayObject([
                        'application/json' => ['schema' => [
                            'type' => 'object',
                            'required' => ['accessToken', 'refreshToken'],
                            'properties' => [
                                'accessToken' => ['type' => 'string'],
                                'refreshToken' => ['type' => 'string'],
                            ],
                        ]],
                    ]),
                ],
                '401' => ['description' => 'Jeton invalide.'],
            ],
            security: [],
        )));

        return $openApi->withPaths($paths);
    }
}