<?php

declare(strict_types=1);

namespace App\Types;

use App\ValueObject\Point;
use Doctrine\DBAL\Types\Type;
use \Doctrine\DBAL\Platforms\AbstractPlatform;

class PointType extends Type
{
    const POINT = 'point';

    public function getName(): string
    {
        return self::POINT;
    }

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return 'geography(POINT, 4326)';
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): ?Point
    {
        if ($value) {
            list($longitude, $latitude) = sscanf($value, 'POINT(%f %f)');
            return new Point($latitude, $longitude);
        }
        return null;
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if ($value instanceof Point) {
            $value = sprintf('POINT(%F %F)', $value->getLongitude(), $value->getLatitude());
        }

        return $value;
    }

    public function canRequireSQLConversion(): bool
    {
        return true;
    }

    public function convertToPHPValueSQL($sqlExpr, $platform): string
    {
        return sprintf('ST_AsText(%s)', $sqlExpr);
    }

    public function convertToDatabaseValueSQL($sqlExpr, AbstractPlatform $platform): string
    {
        return sprintf('ST_SetSRID(ST_PointFromText(%s), 4326)', $sqlExpr);
    }
}
