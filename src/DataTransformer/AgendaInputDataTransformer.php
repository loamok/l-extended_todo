<?php
namespace App\DataTransformer;

use ApiPlatform\Core\DataTransformer\DataTransformerInterface;
use ApiPlatform\Core\Serializer\AbstractItemNormalizer;
use App\DTO\AgendaInput;
use App\Entity\Agenda;

final class AgendaInputDataTransformer implements DataTransformerInterface {
    
    /**
     * {@inheritdoc}
     */
    public function transform($data, string $to, array $context = []) {

        if(array_key_exists(AbstractItemNormalizer::OBJECT_TO_POPULATE, $context) && !is_null($context[AbstractItemNormalizer::OBJECT_TO_POPULATE])) {
            $agenda = $context[AbstractItemNormalizer::OBJECT_TO_POPULATE];
        } else {
            $agenda = new Agenda();
        }
        
        /* @var $data AgendaInput */
        $data->toAgenda($agenda);

        return $agenda;
    }

    /**
     * {@inheritdoc}
     */
    public function supportsTransformation($data, string $to, array $context = []): bool {
        // in the case of an input, the value given here is an array (the JSON decoded).
        // if it's a book we transformed the data already
        if ($data instanceof Agenda) {
          return false;
        }

        return Agenda::class === $to && null !== ($context['input']['class'] ?? null);
    }
}
