<?php

namespace App\Service;

use App\Entity\Pokemon;
use App\Entity\Type;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\String\Slugger\AsciiSlugger;
use Symfony\Component\String\Slugger\SluggerInterface;

class PokemonManager
{
    public function __construct(private SluggerInterface $slugger, private EntityManagerInterface $entityManager)
    {}

    public function createPokemon(array $pokemonList): void
    {
        foreach ($pokemonList as $pokemonData) {
            $pokemon = new Pokemon();
            $pokemon->setPokemonId($pokemonData['']);
            $pokemon->setName($pokemonData['name']);
            $pokemon->setType1($this->getPokemonType($pokemonData['type-1']));
            $pokemon->setType2($this->getPokemonType($pokemonData['type-2']));
            $pokemon->setTotal($pokemonData['total']);
            $pokemon->setHp($pokemonData['hp']);
            $pokemon->setAttack($pokemonData['attack']);
            $pokemon->setDefense($pokemonData['defense']);
            $pokemon->setSpAtk($pokemonData['sp-atk']);
            $pokemon->setSpDef($pokemonData['sp-def']);
            $pokemon->setSpeed($pokemonData['speed']);
            $pokemon->setGeneration($pokemonData['generation']);
            $pokemon->setLegendary($this->strToBool($pokemonData['legendary']));

            $this->entityManager->persist($pokemon);
            $this->entityManager->flush();
        }
    }

    public function createPokemonFromCSV(string $filePath)
    {
        $data = $this->readCsv($filePath);
        $this->createPokemon($data);
    }

    public function updatePokemonType(array $data, Pokemon $pokemon)
    {
        $typeManager = $this->entityManager->getRepository(Type::class);
        if (isset($data['type1']) && $type1 = $typeManager->find($data['type1'])) {
            $pokemon->setType1($type1);
        }
        if (isset($data['type2']) && $type2 = $typeManager->find($data['type2'])) {
            $pokemon->setType2($type2);
        }

        $this->entityManager->flush();
    }

    private function readCsv(string $filePath): array
    {
        $fileResource = fopen($filePath, 'r');
        $data = [];

        $arrayColumn = $this->slugHeader(fgetcsv($fileResource));
        $j = 0;
        while ($pokemonData = fgetcsv($fileResource)) {
            for ($i = 0; $i < count($arrayColumn); $i++) {
                $data[$j][$arrayColumn[$i]] = $pokemonData[$i];
            }
            $j++;
        }

        return $data;
    }

    private function getPokemonType(string $name): ?Type
    {
        $typeRepo = $this->entityManager->getRepository(Type::class);
        $type = $typeRepo->findOneBySlug(
            $this->slugger->slug($name)
        );
        if (!$type && !empty($name)) {
            $type = new Type();
            $type->setName($name);
            $type->setSlug($this->slugger->slug($name)->folded()->toString());

            $this->entityManager->persist($type);
        }

        return $type;
    }

    private function slugHeader(array $data): array
    {
        foreach ($data as &$datum) {
            $datum = $this->slugger->slug($datum)->folded()->toString();
        }

        return $data;
    }

    private function strToBool(string $str): bool
    {
        $str = $this->slugger->slug($str)->folded()->toString();
        if ($str === 'true') {
            return true;
        }

        return false;
    }
}