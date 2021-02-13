<?php

declare(strict_types=1);

namespace App\OpenApi;

use ApiPlatform\Core\OpenApi\Factory\OpenApiFactoryInterface;
use ApiPlatform\Core\OpenApi\OpenApi;
use ApiPlatform\Core\OpenApi\Model;

use Symfony\Component\HttpFoundation\Response;

final class JwtDecorator implements OpenApiFactoryInterface {
    
    private OpenApiFactoryInterface $decorated;

    public function __construct(OpenApiFactoryInterface $decorated) {
        $this->decorated = $decorated;
    }
    
    public function __invoke(array $context = []): OpenApi {
        $openApi = ($this->decorated)($context);
        $schemas = $openApi->getComponents()->getSchemas();
        
        $schemas['Token'] = new ArrayObject([
            'type' => 'object',
            'properties' => [
                'token' => [
                    'type' => 'string',
                    'readOnly' => true,
                ],
            ],
        ]);
        $schemas['Credentials'] = new ArrayObject([
            'type' => 'object',
            'properties' => [
                'username' => [
                    'type' => 'string',
                    'example' => 'api',
                ],
                'password' => [
                    'type' => 'string',
                    'example' => 'api',
                ],
            ],
        ]);

        $pathItem = new Model\PathItem(
            'JWT Token',
            null, null, null, null, 
            new Model\Operation(
                'postCredentialsItem',
                [], 
                [
                    Response::HTTP_OK => [
                        'description' => 'Get JWT token',
                        'content' => [
                            'application/json' => [
                                'schema' => [
                                    '$ref' => '#/components/schemas/Token',
                                ],
                            ],
                        ],
                    ],
                ], 
                'Get JWT token to login.', 
                '', null, [], 
                new Model\RequestBody(
                    'Create new JWT Token', 
                    new \ArrayObject([
                        'application/json' => [
                            'schema' => [
                                '$ref' => '#/components/schemas/Credentials',
                            ],
                        ],
                    ])
                )
            )
        );
        $openApi->getPaths()->addPath('/api/authentication_token', $pathItem);
        
//        dump($openApi);
        
        return $openApi;
    }

}
