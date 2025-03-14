<?php

namespace App\Entity;

use App\Repository\EmployeeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EmployeeRepository::class)]
class Employee
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $surname = null;

    /**
     * @var Collection<int, DailyWorkTime>
     */
    #[ORM\OneToMany(targetEntity: DailyWorkTime::class, mappedBy: 'employee')]
    private Collection $dailyWorkTimes;

    public function __construct()
    {
        $this->dailyWorkTimes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getSurname(): ?string
    {
        return $this->surname;
    }

    public function setSurname(string $surname): static
    {
        $this->surname = $surname;

        return $this;
    }

    /**
     * @return Collection<int, DailyWorkTime>
     */
    public function getDailyWorkTimes(): Collection
    {
        return $this->dailyWorkTimes;
    }

    public function addDailyWorkTime(DailyWorkTime $dailyWorkTime): static
    {
        if (!$this->dailyWorkTimes->contains($dailyWorkTime)) {
            $this->dailyWorkTimes->add($dailyWorkTime);
            $dailyWorkTime->setEmployee($this);
        }

        return $this;
    }

    public function removeDailyWorkTime(DailyWorkTime $dailyWorkTime): static
    {
        if ($this->dailyWorkTimes->removeElement($dailyWorkTime)) {
            // set the owning side to null (unless already changed)
            if ($dailyWorkTime->getEmployee() === $this) {
                $dailyWorkTime->setEmployee(null);
            }
        }

        return $this;
    }
}
