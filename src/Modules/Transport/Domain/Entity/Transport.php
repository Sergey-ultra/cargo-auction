<?php

declare(strict_types=1);

namespace App\Modules\Transport\Domain\Entity;

use App\Modules\Transport\Infrastructure\Repository\TransportRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TransportRepository::class)]
#[ORM\Table(name:"transports")]
class Transport
{

}
