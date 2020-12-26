<?php
namespace App\DataTransformer;

use ApiPlatform\Core\DataTransformer\DataTransformerInterface;
use ApiPlatform\Core\Serializer\AbstractItemNormalizer;
use App\DTO\PersonalizedDelegationRightsInput;
use App\Entity\PersonalizedDelegationRights;

final class PersonalizedDelegationRightsInputDataTransformer implements DataTransformerInterface {
    
    /**
     * {@inheritdoc}
     */
    public function transform($data, string $to, array $context = []) {

        if(array_key_exists(AbstractItemNormalizer::OBJECT_TO_POPULATE, $context) && !is_null($context[AbstractItemNormalizer::OBJECT_TO_POPULATE])) {
            $pdr = $context[AbstractItemNormalizer::OBJECT_TO_POPULATE];
        } else {
            $pdr = new PersonalizedDelegationRights();
        }
        
        /* @var $data PersonalizedDelegationRightsInput */
        $data->toPdr($pdr);

        return $pdr;
    }

    /**
     * {@inheritdoc}
     */
    public function supportsTransformation($data, string $to, array $context = []): bool {
        // in the case of an input, the value given here is an array (the JSON decoded).
        // if it's a book we transformed the data already
        if ($data instanceof PersonalizedDelegationRights) {
          return false;
        }

        return PersonalizedDelegationRights::class === $to && null !== ($context['input']['class'] ?? null);
    }
}
