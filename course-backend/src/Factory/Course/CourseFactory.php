<?php

declare(strict_types=1);

namespace App\Factory\Course;

use App\Dto\Course\CoursePayload;
use App\Entity\Course;

final class CourseFactory
{
    public function createFromPayload(CoursePayload $payload): Course
    {
        $course = new Course();
        $course->setTitle($payload->getTitle());
        $course->setDescription($payload->getDescription());
        $course->setDuration($payload->getDuration());

        return $course;
    }
}
