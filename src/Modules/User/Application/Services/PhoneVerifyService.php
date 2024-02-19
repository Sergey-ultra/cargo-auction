<?php

declare(strict_types=1);

namespace App\Modules\User\Application\PhoneService;

use App\Modules\User\Domain\Entity\Phone;

class PhoneVerifyService
{
    public function verify(Phone $phone): true
    {
        return true;
    }
}
