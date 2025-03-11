<?php

declare(strict_types=1);

namespace App\Modules\User\Infrastructure\Api;


use App\Modules\User\Application\Services\PhoneVerifyService;
use App\Modules\User\Domain\Entity\Phone;
use App\Modules\User\Infrastructure\Repository\PhoneRepository;
use Symfony\Component\Security\Core\User\UserInterface;

final readonly class PhoneApi
{
    public function __construct(private PhoneRepository $repository, private PhoneVerifyService $phoneVerifyService)
    {}
    public function verifyAndSave(?string $phoneString, ?string $mobilePhoneString, UserInterface $user): void
    {
        $phone = new Phone();
        $phone->setPhone($phoneString)->setMobilePhone($mobilePhoneString)->setUser($user);

        $isVerify = $this->phoneVerifyService->verify($phone);
        if ($isVerify) {
            $this->repository->save($phone);
        }
    }
}
