<?php

//declare(strict_types=1);

namespace App\OpenApi;

use ApiPlatform\Core\OpenApi\Factory\OpenApiFactoryInterface;
use ApiPlatform\Core\OpenApi\OpenApi;
use ApiPlatform\Core\OpenApi\Model;

use Symfony\Component\HttpFoundation\Response;

/*final */
class JwtDecorator implements OpenApiFactoryInterface {
    
    private OpenApiFactoryInterface $decorated;

    public function __construct(OpenApiFactoryInterface $decorated) {
        $this->decorated = $decorated;
    }
    
    public function __invoke(array $context = []): OpenApi {
        /* @var $openApi OpenApi */
        $openApi = ($this->decorated)($context);
        $schemas = $openApi->getComponents()->getSchemas();
        /* @var $pathItem Model\PathItem */
        $pathItem = $openApi->getPaths()->getPath('/api/authentication_token');
        /* @var $operation Model\Operation */
        $operation = $pathItem->getPost();
        
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

        $openApi = $openApi->withComponents($openApi->getComponents()->withSchemas($schemas));
        
        $openApi->getPaths()->addPath('/api/authentication_token', $pathItem->withPost(
            $operation->withOperationId('postCredentialsItem')
                ->withResponses([
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
                ])
                ->withSummary('Get JWT token to login.')
                ->withRequestBody(new Model\RequestBody(
                    'Create new JWT Token', 
                    new \ArrayObject([
                        'application/json' => [
                            'schema' => [
                                '$ref' => '#/components/schemas/Credentials',
                            ],
                        ],
                    ])
                ))
        ));
        
//        $openApi->getPaths()->addPath('/api/authentication_token', $pathItem);
        
//        dump($openApi);
        
        return $openApi;
    }

}
