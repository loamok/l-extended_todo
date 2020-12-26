<?php
namespace App\DataTransformer;

use ApiPlatform\Core\DataTransformer\DataTransformerInterface;
use App\DTO\PersonalizedDelegationRights as PersonalizedDelegationRightsOutput;
use App\Entity\PersonalizedDelegationRights;
use DateTimeZone;


final class PersonalizedDelegationRightsOutputDataTransformer implements DataTransformerInterface {
    
    /**
     * {@inheritdoc}
     */
    public function transform($data, string $to, array $context = []) {
        /* @var $data PersonalizedDelegationRights */
        $output = new PersonalizedDelegationRightsOutput();
        $output->fromPdr($data);
        
        return $output;
    }

    /**
     * {@inheritdoc}
     */
    public function supportsTransformation($data, string $to, array $context = []): bool {
        return PersonalizedDelegationRightsOutput::class === $to && $data instanceof PersonalizedDelegationRights;
    }
}
