<?php

declare(strict_types=1);

namespace App\Modules\User\Infrastructure\DTO;

use App\Modules\User\Domain\Entity\User;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\EqualTo;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

final readonly class UserPayloadDTO
{
    public function __construct(
        #[NotBlank(message: 'Имя не должен быть пустой')]
        #[Length(min: 3, minMessage: 'Your name should be at least {{ limit }} characters')]
        public string $name,
        #[NotBlank(message: 'Email не должен быть пустой')]
        #[Email(message: '{{ value }} должен быть валидный')]
        public string $email,
        #[NotBlank(message: 'Телефон не должен быть пустой')]
        #[Regex(pattern: '/\(?([0-9]{3})\)?([ .-]?)([0-9]{3})\2([0-9]{4})/')]
        public string $phone,
        #[NotBlank(message: 'Пароль не должен быть пустой')]
        #[Length(min: 6, max: 255, minMessage: 'Your password should be at least {{ limit }} characters')]
        public string $password,
        #[NotBlank(message: 'Подтверждение пароля не должен быть пустым')]
        #[EqualTo(propertyPath: 'password')]
        public string $password_confirm,
        #[NotBlank(message: 'Роль не должна быть пустой')]
        #[Choice(options: User::ROLE_VALUES)]
        public string $role,
    ){}
}
