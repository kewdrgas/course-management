<?php

declare(strict_types=1);

namespace App\Domain\Course\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Domain\Course\Service\CourseService;
use App\Dto\Course\CoursePayload;
use App\Entity\Course;
use App\Factory\Course\CourseFactory;

class CourseProcessor implements ProcessorInterface
{
    public function __construct(
        private readonly CourseFactory $factory,
        private readonly CourseService $service,
    ) {
    }

    /**
     * @param CoursePayload $data
     */
    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): Course
    {
        $course = $this->factory->createFromPayload($data);

        $this->service->create($course);

        return $course;
    }
}
