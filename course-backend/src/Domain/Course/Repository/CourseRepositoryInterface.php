<?php

declare(strict_types=1);

namespace App\Domain\Course\Repository;

use App\Dto\Course\CoureDto;
use DateTimeImmutable;

interface CourseRepositoryInterface
{
    public function getAverageDuration(): float;

    /** @return CoureDto[] */
    public function findPublishedBetween(DateTimeImmutable $from, DateTimeImmutable $to): array;
}
