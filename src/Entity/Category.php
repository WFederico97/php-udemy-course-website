<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CategoryRepository::class)]
class Category
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\OneToMany(targetEntity: Dishes::class, mappedBy: 'category')]
    private Collection $dish;

    public function __construct()
    {
        $this->dish = new ArrayCollection();
    }

    // #[ORM\OneToMany(targetEntity:"App\Entity\Dishes", mappedBy: 'category')]
    // private $dish ;

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

    /**
     * @return Collection<int, Dishes>
     */
    public function getDish(): Collection
    {
        return $this->dish;
    }

    public function addDish(Dishes $dish): static
    {
        if (!$this->dish->contains($dish)) {
            $this->dish->add($dish);
            $dish->setCategory($this);
        }

        return $this;
    }

    public function removeDish(Dishes $dish): static
    {
        if ($this->dish->removeElement($dish)) {
            // set the owning side to null (unless already changed)
            if ($dish->getCategory() === $this) {
                $dish->setCategory(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->name;
    }
}
