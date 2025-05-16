<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use App\Controller\CourseApiController;
use App\Domain\Course\Enum\Status;
use App\Domain\Course\State\CourseProcessor;
use App\Dto\Course\CoursePayload;
use App\Repository\CourseRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CourseRepository::class)]
#[ORM\Table(name: 'courses')]
#[ApiResource(
    input: CoursePayload::class,
    operations: [
        new Get(),
        new GetCollection(
            uriTemplate: '/courses',
            controller: CourseApiController::class . '::listCourses',
            extraProperties: [
                'openapi_context' => [
                    'summary' => 'List courses with optional filters and pagination',
                    'parameters' => [
                        [
                            'name' => 'status',
                            'in' => 'query',
                            'schema' => ['type' => 'string', 'enum' => ['draft', 'published', 'archived']],
                            'required' => false,
                        ],
                        [
                            'name' => 'page',
                            'in' => 'query',
                            'schema' => ['type' => 'integer'],
                            'required' => false,
                        ],
                        [
                            'name' => 'limit',
                            'in' => 'query',
                            'schema' => ['type' => 'integer'],
                            'required' => false,
                        ],
                    ],
                    'responses' => [
                        200 => [
                            'description' => 'Returns paginated list of courses',
                        ],
                    ],
                ]
            ],
            paginationEnabled: false,
            read: false,
            output: false
        ),
        new Post(input: CoursePayload::class),
        new Post(
            uriTemplate: '/courses/{id}/edit',
            controller: CourseApiController::class . '::edit',
            input: CoursePayload::class,
            name: 'edit_course',
        ),
        new Post(
            uriTemplate: '/courses/{id}/duplicate',
            controller: CourseApiController::class . '::duplicate',
            read: false,
            write: false
        ),
        new Post(
            uriTemplate: '/courses/{id}/publish',
            controller: CourseApiController::class . '::publish',
            read: false,
            write: false
        ),
        new Post(
            uriTemplate: '/courses/{id}/archive',
            controller: CourseApiController::class . '::archive',
            read: false,
            write: false
        )
    ],
    normalizationContext: ['groups' => ['course:read']],
    processor: CourseProcessor::class
)]
class Course
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\GeneratedValue(strategy: 'NONE')]
    #[Groups(['course:read'])]
    private ?Uuid $id = null;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank]
    #[Groups(['course:read'])]
    private string $title;

    #[ORM\Column(type: 'text', nullable: true)]
    #[Groups(['course:read'])]
    private ?string $description = null;

    #[ORM\Column(type: 'integer')]
    #[Assert\Positive]
    #[Groups(['course:read'])]
    private int $duration;

    #[ORM\Column(type: 'string', enumType: Status::class)]
    #[Groups(['course:read'])]
    private Status $status = Status::Draft;

    #[ORM\Column(type: 'datetime_immutable')]
    #[Groups(['course:read'])]
    private \DateTimeImmutable $createdAt;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    #[Groups(['course:read'])]
    private ?\DateTimeImmutable $publishedAt = null;

    public function __construct()
    {
        $this->id = Uuid::v4();
        $this->createdAt = new \DateTimeImmutable();
        $this->status = Status::Draft;
    }

    public function getId(): ?string
    {
        return $this->id?->toRfc4122();
    }
    public function getTitle(): string
    {
        return $this->title;
    }
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }
    public function getDescription(): ?string
    {
        return $this->description;
    }
    public function setDescription(?string $desc): void
    {
        $this->description = $desc;
    }
    public function getDuration(): int
    {
        return $this->duration;
    }
    public function setDuration(int $duration): void
    {
        $this->duration = $duration;
    }
    public function getStatus(): Status
    {
        return $this->status;
    }
    public function setStatus(Status $status): void
    {
        $this->status = $status;
    }
    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }
    public function getPublishedAt(): ?\DateTimeImmutable
    {
        return $this->publishedAt;
    }
    public function setPublishedAt(?\DateTimeImmutable $publishedAt): void
    {
        $this->publishedAt = $publishedAt;
    }

    public function publish(): void
    {
        if (empty($this->title) || $this->duration <= 0) {
            throw new \DomainException('Cannot publish course without title and valid duration.');
        }

        $this->status = Status::Published;
        $this->publishedAt = new \DateTimeImmutable();
    }

    public function archive(): void
    {
        $this->status = Status::Archived;
    }

    public function isEditable(): bool
    {
        return $this->status !== Status::Archived;
    }
}
