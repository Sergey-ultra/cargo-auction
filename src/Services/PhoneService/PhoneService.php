<?php

declare(strict_types=1);

namespace App\Services\PhoneService;

use App\Entity\Phone;
use App\Repository\PhoneRepository;

class PhoneService
{
    public function __construct(private PhoneRepository $repository)
    {}
    public function verifyAndSave(Phone $phone): true
    {
        $this->repository->save($phone);
        return true;
    }
}
