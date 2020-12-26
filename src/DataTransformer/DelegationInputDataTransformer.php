<?php
namespace App\DataTransformer;

use ApiPlatform\Core\DataTransformer\DataTransformerInterface;
use ApiPlatform\Core\Serializer\AbstractItemNormalizer;
use App\DTO\DelegationInput;
use App\Entity\Delegation;

final class DelegationInputDataTransformer implements DataTransformerInterface {
    
    /**
     * {@inheritdoc}
     */
    public function transform($data, string $to, array $context = []) {

        if(array_key_exists(AbstractItemNormalizer::OBJECT_TO_POPULATE, $context) && !is_null($context[AbstractItemNormalizer::OBJECT_TO_POPULATE])) {
            $delegation = $context[AbstractItemNormalizer::OBJECT_TO_POPULATE];
        } else {
            $delegation = new Delegation();
        }
        
        /* @var $data DelegationInput */
        $data->toDelegation($delegation);

        return $delegation;
    }

    /**
     * {@inheritdoc}
     */
    public function supportsTransformation($data, string $to, array $context = []): bool {
        // in the case of an input, the value given here is an array (the JSON decoded).
        // if it's a book we transformed the data already
        if ($data instanceof Delegation) {
          return false;
        }

        return Delegation::class === $to && null !== ($context['input']['class'] ?? null);
    }
}
