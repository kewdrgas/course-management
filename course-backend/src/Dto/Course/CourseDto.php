<?php

declare(strict_types=1);

namespace App\Dto\Course;

use App\Domain\Course\Enum\Status;

final class CourseDto
{
    public function __construct(
        private string $id,
        private string $title,
        private ?string $description,
        private int $duration,
        private Status $status,
        private \DateTimeImmutable $createdAt,
        private ?\DateTimeImmutable $publishedAt
    ) {
    }

    public function getId(): string
    {
        return $this->id;
    }
    public function getTitle(): string
    {
        return $this->title;
    }
    public function getDescription(): ?string
    {
        return $this->description;
    }
    public function getDuration(): int
    {
        return $this->duration;
    }
    public function getStatus(): Status
    {
        return $this->status;
    }
    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }
    public function getPublishedAt(): ?\DateTimeImmutable
    {
        return $this->publishedAt;
    }
}
