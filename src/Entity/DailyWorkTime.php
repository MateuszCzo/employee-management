<?php

namespace App\Entity;

use App\Constants\Constants;
use App\Repository\DailyWorkTimeRepository;
use DateTimeInterface;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

#[ORM\Entity(repositoryClass: DailyWorkTimeRepository::class)]
class DailyWorkTime
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'dailyWorkTimes')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotNull]
    #[Assert\NotBlank]
    #[Assert\Type(Employee::class)]
    private ?Employee $employee = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Assert\NotNull]
    #[Assert\NotBlank]
    #[Assert\Type(DateTimeInterface::class)]
    private ?\DateTimeInterface $startDateTime = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Assert\NotNull]
    #[Assert\NotBlank]
    #[Assert\Type(DateTimeInterface::class)]
    #[Assert\GreaterThan(propertyPath: 'startDateTime')]
    private ?\DateTimeInterface $endDateTime = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Assert\NotNull]
    #[Assert\NotBlank]
    #[Assert\Type(DateTimeInterface::class)]
    private ?\DateTimeInterface $date = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmployee(): ?Employee
    {
        return $this->employee;
    }

    public function setEmployee(?Employee $employee): static
    {
        $this->employee = $employee;

        return $this;
    }

    public function getStartDateTime(): ?\DateTimeInterface
    {
        return $this->startDateTime;
    }

    public function setStartDateTime(\DateTimeInterface $startDateTime): static
    {
        $this->startDateTime = $startDateTime;

        if ($startDateTime > $this->endDateTime) {
            $this->date = $startDateTime;
        }

        return $this;
    }

    public function getEndDateTime(): ?\DateTimeInterface
    {
        return $this->endDateTime;
    }

    public function setEndDateTime(\DateTimeInterface $endDateTime): static
    {
        $this->endDateTime = $endDateTime;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }

    #[Assert\Callback]
    public function validate(ExecutionContextInterface $context, mixed $payload): void
    {
        // check before validating if the required fields are not empty
        if ($this->startDateTime === null || 
            $this->endDateTime === null || 
            $this->employee === null || 
            $this->date === null
        ) {
            return;
        }

        // check if endDateTime is in the same day as startDateTime
        if ($this->startDateTime->format('Y-m-d') !== $this->endDateTime->format('Y-m-d')) {
            $context->buildViolation('End date must be in the same day as start date')
                ->atPath('endDateTime')
                ->addViolation();
        }

        // check if the employee has already worked on this day
        $alreadyWorked = $this->employee->getDailyWorkTimes()->filter(function($dailyWorkTime) {
            return $dailyWorkTime->getDate()->format('Y-m-d') === $this->date->format('Y-m-d');
        });
        if ($alreadyWorked->count() > 0) {
            $context->buildViolation('Employee has already worked on this day')
                ->atPath('date')
                ->addViolation();
        }

        // check if daily max hours are exceeded
        $diff = $this->startDateTime->diff($this->endDateTime);
        $allowedDailyWorkHours = (float)$diff->h + (float)$diff->i / 60;

        if ($allowedDailyWorkHours > Constants::MAX_DAILY_WORK_HOURS) {
            $context->buildViolation('Daily work hours exceeded')
                ->atPath('endDateTime')
                ->addViolation();
        }
    }
}
