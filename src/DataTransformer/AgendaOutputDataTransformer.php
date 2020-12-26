<?php
namespace App\DataTransformer;

use ApiPlatform\Core\DataTransformer\DataTransformerInterface;
use App\DTO\Agenda as AgendaOutput;
use App\Entity\Agenda;
use DateTimeZone;


final class AgendaOutputDataTransformer implements DataTransformerInterface {
    
    /**
     * {@inheritdoc}
     */
    public function transform($data, string $to, array $context = []) {
        /* @var $data Agenda */
        $output = new AgendaOutput();
        $output->fromAgenda($data);
        
        return $output;
    }

    /**
     * {@inheritdoc}
     */
    public function supportsTransformation($data, string $to, array $context = []): bool {
        return AgendaOutput::class === $to && $data instanceof Agenda;
    }
}
