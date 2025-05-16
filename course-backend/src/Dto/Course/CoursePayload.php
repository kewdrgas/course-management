<?php

declare(strict_types=1);

namespace App\Dto\Course;

use Symfony\Component\Validator\Constraints as Assert;

final class CoursePayload
{
    #[Assert\NotBlank]
    private string $title;

    private ?string $description = null;

    #[Assert\Positive]
    private int $duration;

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

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    public function getDuration(): int
    {
        return $this->duration;
    }

    public function setDuration(int $duration): void
    {
        $this->duration = $duration;
    }
}
