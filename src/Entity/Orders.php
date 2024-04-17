<?php

namespace App\Entity;

use App\Repository\OrdersRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrdersRepository::class)]
class Orders
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $counter = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCounter(): ?string
    {
        return $this->counter;
    }

    public function setCounter(string $counter): static
    {
        $this->counter = $counter;

        return $this;
    }
}
