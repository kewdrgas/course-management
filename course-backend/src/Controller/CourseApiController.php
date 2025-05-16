<?php

declare(strict_types=1);

namespace App\Controller;

use App\Domain\Course\Repository\CourseRepositoryInterface;
use App\Domain\Course\Service\CourseDuplicator;
use App\Domain\Course\Service\CourseService;
use App\Dto\Course\CourseDto;
use App\Dto\Course\CoursePayload;
use App\Entity\Course;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;

class CourseApiController extends AbstractController
{
    public function __construct(
        private readonly CourseRepositoryInterface $repository,
        private readonly CourseDuplicator $duplicator,
        private readonly CourseService $service,
    ) {
    }

    #[Route('/api/courses/average-duration', name: 'average_duration', methods: ['GET'])]
    public function averageDuration(): JsonResponse
    {
        return new JsonResponse([
            'average_duration' => round($this->repository->getAverageDuration(), 2)
        ]);
    }

    #[Route('/api/courses/published-by-date-range', name: 'published_between_dates', methods: ['GET'])]
    public function publishedBetweenDates(): JsonResponse
    {
        $from = new \DateTimeImmutable($_GET['from'] ?? '1970-01-01');
        $to = new \DateTimeImmutable($_GET['to'] ?? 'now');

        /** @var CourseDto[] $courses */
        $courses = $this->repository->findPublishedBetween($from, $to);

        return $this->json($courses);
    }

    #[Route('/api/courses', name: 'list_courses', methods: ['GET'])]
    public function listCourses(): JsonResponse
    {
        $status = $_GET['status'] ?? null;
        $page = (int) ($_GET['page'] ?? 1);
        $limit = (int) ($_GET['limit'] ?? 5);

        $courses = $status
            ? $this->repository->findByStatus($status, $page, $limit)
            : $this->repository->findPaginated($page, $limit);

        $total = $status
            ? $this->repository->countByStatus($status)
            : $this->repository->countAll();

        return $this->json([
            'total' => $total,
            'page' => $page,
            'limit' => $limit,
            'courses' => $courses,
        ]);
    }

    #[Route('/api/courses/{id}/duplicate', name: 'duplicate_course', methods: ['POST'])]
    public function duplicate(Course $course): JsonResponse
    {
        $copy = $this->duplicator->duplicate($course);

        return $this->json([
            'id' => $copy->getId(),
            'title' => $copy->getTitle(),
            'status' => $copy->getStatus()->value,
        ], 201);
    }

    #[Route('/api/courses/{id}/publish', name: 'publish_course', methods: ['POST'])]
    public function publish(Course $course): JsonResponse
    {
        try {
            $this->service->publish($course);

            return $this->json(['status' => 'published']);
        } catch (\DomainException $e) {
            return $this->json(['error' => $e->getMessage()], 400);
        }
    }

    #[Route('/api/courses/{id}/archive', name: 'archive_course', methods: ['POST'])]
    public function archive(Course $course): JsonResponse
    {
        try {
            $this->service->archive($course);

            return $this->json(['status' => 'archived']);
        } catch (\DomainException $e) {
            return $this->json(['error' => $e->getMessage()], 400);
        }
    }

    #[Route('/api/courses/{id}/edit', name: 'edit_course', methods: ['POST'])]
    public function edit(Course $course, #[MapRequestPayload] CoursePayload $payload): JsonResponse
    {
        try {
            $this->service->edit($course, $payload);

            return $this->json([
                'id' => $course->getId(),
                'title' => $course->getTitle(),
                'status' => $course->getStatus()->value,
            ]);
        } catch (\DomainException $e) {
            return $this->json(['error' => $e->getMessage()], 400);
        }
    }
}
