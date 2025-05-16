<?php

declare(strict_types=1);

namespace App\Repository;

use App\Domain\Course\Enum\Status;
use App\Domain\Course\Repository\CourseRepositoryInterface;
use App\Dto\Course\CourseDto;
use App\Entity\Course;
use DateTimeImmutable;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class CourseRepository extends ServiceEntityRepository implements CourseRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Course::class);
    }

    public function getAverageDuration(): float
    {
        return (float) $this->createQueryBuilder('c')
            ->select('AVG(c.duration)')
            ->getQuery()
            ->getSingleScalarResult();
    }

    /** @return CourseDto[] */
    public function findPublishedBetween(DateTimeImmutable $from, DateTimeImmutable $to): array
    {
        $qb = $this->createQueryBuilder('c')
            ->where('c.status = :status')
            ->andWhere('c.publishedAt BETWEEN :from AND :to')
            ->setParameter('status', Status::Published)
            ->setParameter('from', $from)
            ->setParameter('to', $to);

        $courses = $qb->getQuery()->getResult();

        return $this->mapToDtoArray($courses);
    }

    /** @return CourseDto[] */
    public function findByStatus(string $status, int $page = 1, int $limit = 5): array
    {
        $query = $this->createQueryBuilder('c')
            ->where('c.status = :status')
            ->setParameter('status', $status)
            ->orderBy('c.createdAt', 'DESC')
            ->setFirstResult(($page - 1) * $limit)
            ->setMaxResults($limit);

        $courses = $query->getQuery()->getResult();

        return $this->mapToDtoArray($courses);
    }

    public function countByStatus(string $status): int
    {
        return (int) $this->createQueryBuilder('c')
            ->select('COUNT(c.id)')
            ->where('c.status = :status')
            ->setParameter('status', $status)
            ->getQuery()
            ->getSingleScalarResult();
    }

    /** @return CourseDto[] */
    public function findPaginated(int $page = 1, int $limit = 5): array
    {
        $courses = $this->createQueryBuilder('c')
            ->orderBy('c.createdAt', 'DESC')
            ->setFirstResult(($page - 1) * $limit)
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();

        return $this->mapToDtoArray($courses);
    }

    public function countAll(): int
    {
        return (int) $this->createQueryBuilder('c')
            ->select('COUNT(c.id)')
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * @param Course[] $courses
     * @return CourseDto[]
     */
    private function mapToDtoArray(array $courses): array
    {
        return array_map(fn (Course $c) => new CourseDto(
            id: $c->getId(),
            title: $c->getTitle(),
            description: $c->getDescription(),
            duration: $c->getDuration(),
            status: $c->getStatus(),
            createdAt: $c->getCreatedAt(),
            publishedAt: $c->getPublishedAt()
        ), $courses);
    }
}
