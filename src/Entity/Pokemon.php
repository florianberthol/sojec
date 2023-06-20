<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\BooleanFilter;
use ApiPlatform\Doctrine\Orm\Filter\NumericFilter;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use App\Controller\UpdatePokemonController;
use App\Repository\PokemonRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: PokemonRepository::class)]
#[ApiResource(
    operations: [
        new GetCollection(
            uriTemplate: '/',
        ), new Patch(
            uriTemplate: '/{id}',
            controller: UpdatePokemonController::class,
            hydraContext: ['write'],
            security: "is_granted('EDIT', object)"
        ), new Delete(
            uriTemplate: '/{id}',
            security: "is_granted('DEL', object)"
        )
    ],
    routePrefix: '/pokemon',
    normalizationContext: ['groups' => ['read']],
    denormalizationContext: ['groups' => ['write']],
    paginationClientItemsPerPage: true,
    paginationItemsPerPage: 50,
)]

#[ApiFilter(NumericFilter::class, properties: [ 'id', 'pokemonId', 'generation'])]
#[ApiFilter(BooleanFilter::class, properties: [ 'legendary'])]
#[ApiFilter(SearchFilter::class, properties: [ 'name' => 'ipartial', 'type1.name' => 'ipartial', 'type2.name' => 'ipartial',])]
class Pokemon
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['read'])]
    private ?int $id = null;

    #[ORM\Column]
    #[Groups(['read'])]
    private ?int $pokemonId = null;

    #[ORM\Column(length: 255)]
    #[Groups(['read', 'write'])]
    #[Assert\NotBlank()]
    private ?string $name = null;

    #[ORM\ManyToOne(inversedBy: 'pokemonType1')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['read', 'write'])]
    #[Assert\NotNull()]
    private ?Type $type1 = null;

    #[ORM\ManyToOne(inversedBy: 'pokemonType2')]
    #[Groups(['read', 'write'])]
    private ?Type $type2 = null;

    #[ORM\Column]
    #[Groups(['read'])]
    private ?int $total = null;

    #[ORM\Column]
    #[Groups(['read'])]
    private ?int $hp = null;

    #[ORM\Column]
    #[Groups(['read'])]
    private ?int $attack = null;

    #[ORM\Column]
    #[Groups(['read'])]
    private ?int $defense = null;

    #[ORM\Column]
    #[Groups(['read'])]
    private ?int $spAtk = null;

    #[ORM\Column]
    #[Groups(['read'])]
    private ?int $spDef = null;

    #[ORM\Column]
    #[Groups(['read'])]
    private ?int $speed = null;

    #[ORM\Column]
    #[Groups(['read', 'write'])]
    #[Assert\NotBlank()]
    private ?int $generation = null;

    #[ORM\Column]
    #[Groups(['read', 'write'])]
    #[Assert\NotNull()]
    private ?bool $legendary = null;

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

    public function getType1(): ?Type
    {
        return $this->type1;
    }

    public function setType1(?Type $type1): static
    {
        $this->type1 = $type1;

        return $this;
    }

    public function getType2(): ?Type
    {
        return $this->type2;
    }

    public function setType2(?Type $type2): static
    {
        $this->type2 = $type2;

        return $this;
    }

    public function getTotal(): ?int
    {
        return $this->total;
    }

    public function setTotal(int $total): static
    {
        $this->total = $total;

        return $this;
    }

    public function getHp(): ?int
    {
        return $this->hp;
    }

    public function setHp(int $hp): static
    {
        $this->hp = $hp;

        return $this;
    }

    public function getAttack(): ?int
    {
        return $this->attack;
    }

    public function setAttack(int $attack): static
    {
        $this->attack = $attack;

        return $this;
    }

    public function getDefense(): ?int
    {
        return $this->defense;
    }

    public function setDefense(int $defense): static
    {
        $this->defense = $defense;

        return $this;
    }

    public function getSpAtk(): ?int
    {
        return $this->spAtk;
    }

    public function setSpAtk(int $spAtk): static
    {
        $this->spAtk = $spAtk;

        return $this;
    }

    public function getSpDef(): ?int
    {
        return $this->spDef;
    }

    public function setSpDef(int $spDef): static
    {
        $this->spDef = $spDef;

        return $this;
    }

    public function getSpeed(): ?int
    {
        return $this->speed;
    }

    public function setSpeed(int $speed): static
    {
        $this->speed = $speed;

        return $this;
    }

    public function getGeneration(): ?int
    {
        return $this->generation;
    }

    public function setGeneration(int $generation): static
    {
        $this->generation = $generation;

        return $this;
    }

    public function isLegendary(): ?bool
    {
        return $this->legendary;
    }

    public function setLegendary(bool $legendary): static
    {
        $this->legendary = $legendary;

        return $this;
    }

    public function getPokemonId(): ?int
    {
        return $this->pokemonId;
    }

    public function setPokemonId(int $pokemonId): static
    {
        $this->pokemonId = $pokemonId;

        return $this;
    }
}
