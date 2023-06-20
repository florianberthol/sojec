<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\TypeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;


#[ORM\Entity(repositoryClass: TypeRepository::class)]
class Type
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['read'])]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    #[Groups(['read'])]
    private ?string $slug = null;

    #[ORM\OneToMany(mappedBy: 'type1', targetEntity: Pokemon::class, orphanRemoval: true)]
    private Collection $pokemonType1;

    #[ORM\OneToMany(mappedBy: 'type2', targetEntity: Pokemon::class)]
    private Collection $pokemonType2;

    public function __construct()
    {
        $this->pokemonType1 = new ArrayCollection();
        $this->pokemonType2 = new ArrayCollection();
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

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): static
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * @return Collection<int, Pokemon>
     */
    public function getPokemonType1(): Collection
    {
        return $this->pokemonType1;
    }

    public function addPokemon(Pokemon $pokemon): static
    {
        if (!$this->pokemonType1->contains($pokemon)) {
            $this->pokemonType1->add($pokemon);
            $pokemon->setType1($this);
        }

        return $this;
    }

    public function removePokemon(Pokemon $pokemon): static
    {
        if ($this->pokemonType1->removeElement($pokemon)) {
            // set the owning side to null (unless already changed)
            if ($pokemon->getType1() === $this) {
                $pokemon->setType1(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Pokemon>
     */
    public function getPokemonType2(): Collection
    {
        return $this->pokemonType2;
    }

    public function addPokemonType2(Pokemon $pokemonType2): static
    {
        if (!$this->pokemonType2->contains($pokemonType2)) {
            $this->pokemonType2->add($pokemonType2);
            $pokemonType2->setType2($this);
        }

        return $this;
    }

    public function removePokemonType2(Pokemon $pokemonType2): static
    {
        if ($this->pokemonType2->removeElement($pokemonType2)) {
            // set the owning side to null (unless already changed)
            if ($pokemonType2->getType2() === $this) {
                $pokemonType2->setType2(null);
            }
        }

        return $this;
    }
}
