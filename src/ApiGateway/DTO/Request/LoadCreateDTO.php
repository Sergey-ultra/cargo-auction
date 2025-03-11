<?php

declare(strict_types=1);

namespace App\ApiGateway\DTO\Request;

use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;

final readonly class LoadCreateDTO
{
    public function __construct(
        public LoadingDTO $loading,
        public UnloadingDTO $unloading,
        public TruckDTO $truck,
        public PaymentDTO $payment,
        public string $note,
        #[NotBlank(message: 'Должен быть хотя бы один контакт')]
        #[Type('array')]
        public array $contactIds,
        public array $files,
    ) {
    }
}
