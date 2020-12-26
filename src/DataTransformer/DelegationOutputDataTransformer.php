<?php
namespace App\DataTransformer;

use ApiPlatform\Core\DataTransformer\DataTransformerInterface;
use App\DTO\Delegation as DelegationOutput;
use App\Entity\Delegation;
use DateTimeZone;


final class DelegationOutputDataTransformer implements DataTransformerInterface {
    
    /**
     * {@inheritdoc}
     */
    public function transform($data, string $to, array $context = []) {
        /* @var $data Delegation */
        $output = new DelegationOutput();
        $output->fromDelegation($data);
        
        return $output;
    }

    /**
     * {@inheritdoc}
     */
    public function supportsTransformation($data, string $to, array $context = []): bool {
        return DelegationOutput::class === $to && $data instanceof Delegation;
    }
}
