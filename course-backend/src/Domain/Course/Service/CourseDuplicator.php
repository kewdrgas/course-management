<?php

declare(strict_types=1);

namespace App\Domain\Course\Service;

use App\Domain\Course\Enum\Status;
use App\Entity\Course;
use Doctrine\ORM\EntityManagerInterface;

class CourseDuplicator
{
    public function __construct(private EntityManagerInterface $em)
    {
    }

    public function duplicate(Course $original): Course
    {
        $copy = new Course();
        $copy->setTitle($original->getTitle());
        $copy->setDescription($original->getDescription());
        $copy->setDuration($original->getDuration());
        $copy->setStatus(Status::Draft);
        $copy->setPublishedAt(null);

        $this->em->persist($copy);
        $this->em->flush();

        return $copy;
    }
}
