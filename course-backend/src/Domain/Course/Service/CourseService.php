<?php

declare(strict_types=1);

namespace App\Domain\Course\Service;

use App\Domain\Course\Enum\Status;
use App\Dto\Course\CoursePayload;
use App\Entity\Course;
use Doctrine\ORM\EntityManagerInterface;

final class CourseService
{
    public function __construct(
        private readonly EntityManagerInterface $em,
    ) {
    }

    public function create(Course $course): Course
    {
        $this->em->persist($course);
        $this->em->flush();

        return $course;
    }

    public function edit(Course $course, CoursePayload $payload): void
    {
        if (!$course->isEditable()) {
            throw new \DomainException('Cannot edit an archived course.');
        }

        $course->setTitle($payload->getTitle());
        $course->setDescription($payload->getDescription());
        $course->setDuration($payload->getDuration());

        $this->em->flush();
    }

    public function publish(Course $course): void
    {
        if (empty($course->getTitle()) || $course->getDuration() <= 0) {
            throw new \DomainException('Course must have a title and positive duration to be published.');
        }

        $course->setStatus(Status::Published);
        $course->setPublishedAt(new \DateTimeImmutable());

        $this->em->flush();
    }

    public function archive(Course $course): void
    {
        if ($course->getStatus() === Status::Archived) {
            throw new \DomainException('Course is already archived.');
        }

        $course->setStatus(Status::Archived);

        $this->em->flush();
    }
}
