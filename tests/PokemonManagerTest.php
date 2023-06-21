<?php

namespace App\Tests;

use App\Entity\Pokemon;
use App\Entity\Type;
use App\Repository\PokemonRepository;
use App\Service\PokemonManager;
use App\Service\Slugger;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;

class PokemonManagerTest extends TestCase
{
    private $slugger;

    private $em;

    public function setUp(): void
    {
        $this->slugger = $this->getMockBuilder(Slugger::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->em = $this->getMockBuilder(EntityManagerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    public function testCreatePokemons(): void
    {
        $pokemonRepository = $this->getMockBuilder(PokemonRepository::class)
            ->disableOriginalConstructor()
            ->addMethods(['findOneBySlug'])
            ->getMock();

        $pokemonRepository->expects($this->exactly(4))
            ->method('findOneBySlug')
            ->willReturnOnConsecutiveCalls(
                $this->createTypeEntity('grass'),
                $this->createTypeEntity('poison'),
                $this->createTypeEntity('grass'),
                $this->createTypeEntity('poison'),
            );

        $this->em->expects($this->exactly(4))
            ->method('getRepository')
            ->willReturn($pokemonRepository);

        $reflexionPokemonManager = new \ReflectionClass(PokemonManager::class);
        $createPokemons = $reflexionPokemonManager->getMethod('createPokemons');
        $createPokemons->setAccessible(true);

        $pokemonManager = new PokemonManager($this->slugger, $this->em);

        $data = [
            [
                '' => '1',
                'name' => 'Bulbasaur',
                'type-1' => 'Grass',
                'type-2' => 'Poison',
                'total' => 318,
                'hp' => 45,
                'attack' => 49,
                'defense' => 49,
                'sp-atk' => 65,
                'sp-def' => 65,
                'speed' => 45,
                'generation' => 1,
                'legendary' => 'False',
            ], [
                '' => '2',
                'name' => 'Ivysaur',
                'type-1' => 'Grass',
                'type-2' => 'Poison',
                'total' => 405,
                'hp' => 60,
                'attack' => 62,
                'defense' => 63,
                'sp-atk' => 80,
                'sp-def' => 80,
                'speed' => 60,
                'generation' => 1,
                'legendary' => 'False',
            ]
        ];

        /** @var array<Pokemon> $result */
        $result = $createPokemons->invokeArgs($pokemonManager, [$data]);
        $this->assertEquals(2, count($result));
        $this->assertInstanceOf(Pokemon::class, $result[0]);
        $this->assertInstanceOf(Pokemon::class, $result[1]);
        $this->assertInstanceOf(Type::class, $result[0]->getType1());
        $this->assertInstanceOf(Type::class, $result[1]->getType2());
        $this->assertEquals('Bulbasaur', $result[0]->getName());
        $this->assertEquals('Ivysaur', $result[1]->getName());
        $this->assertEquals(318, $result[0]->getTotal());
        $this->assertEquals(405, $result[1]->getTotal());
        $this->assertEquals(45, $result[0]->getHp());
        $this->assertEquals(60, $result[1]->getHp());
        $this->assertEquals(49, $result[0]->getAttack());
        $this->assertEquals(62, $result[1]->getAttack());
        $this->assertEquals(49, $result[0]->getDefense());
        $this->assertEquals(63, $result[1]->getDefense());
        $this->assertEquals(65, $result[0]->getSpAtk());
        $this->assertEquals(80, $result[1]->getSpAtk());
        $this->assertEquals(65, $result[0]->getSpDef());
        $this->assertEquals(80, $result[1]->getSpDef());
        $this->assertEquals(45, $result[0]->getSpeed());
        $this->assertEquals(60, $result[1]->getSpeed());
        $this->assertEquals(1, $result[0]->getGeneration());
        $this->assertEquals(1, $result[1]->getGeneration());
        $this->assertFalse($result[0]->isLegendary());
        $this->assertFalse($result[1]->isLegendary());
        $this->assertEquals('grass', $result[0]->getType1()->getName());
        $this->assertEquals('poison', $result[0]->getType2()->getName());
        $this->assertEquals('grass', $result[1]->getType1()->getName());
        $this->assertEquals('poison', $result[1]->getType2()->getName());
    }

    public function testReadCsv(): void
    {
        $reflexionPokemonManager = new \ReflectionClass(PokemonManager::class);
        $readCsv = $reflexionPokemonManager->getMethod('readCsv');
        $readCsv->setAccessible(true);

        $this->slugger->expects($this->exactly(13))
            ->method('slug')
            ->willReturnOnConsecutiveCalls('','name','type_1','type_2', 'total', 'hp', 'attack', 'defense','sp_atk', 'sp_def', 'speed', 'generation', 'legendary');

        $pokemonManager = new PokemonManager($this->slugger, $this->em);
        $result = $readCsv->invokeArgs($pokemonManager, [__DIR__ . '/test.csv']);

        $this->assertEquals(2, count($result));
        $this->assertEquals(1, $result[0]['']);
        $this->assertEquals('Bulbasaur', $result[0]['name']);
        $this->assertEquals('Grass', $result[0]['type_1']);
        $this->assertEquals('Poison', $result[0]['type_2']);
        $this->assertEquals(318, $result[0]['total']);
        $this->assertEquals(45, $result[0]['hp']);
        $this->assertEquals(49, $result[0]['attack']);
        $this->assertEquals(49, $result[0]['defense']);
        $this->assertEquals(65, $result[0]['sp_atk']);
        $this->assertEquals(65, $result[0]['sp_def']);
        $this->assertEquals(45, $result[0]['speed']);
        $this->assertEquals(1, $result[0]['generation']);
        $this->assertEquals('False', $result[0]['legendary']);

        $this->assertEquals(2, $result[1]['']);
        $this->assertEquals('Ivysaur', $result[1]['name']);
        $this->assertEquals('Grass', $result[1]['type_1']);
        $this->assertEquals('Poison', $result[1]['type_2']);
        $this->assertEquals(405, $result[1]['total']);
        $this->assertEquals(60, $result[1]['hp']);
        $this->assertEquals(62, $result[1]['attack']);
        $this->assertEquals(63, $result[1]['defense']);
        $this->assertEquals(80, $result[1]['sp_atk']);
        $this->assertEquals(80, $result[1]['sp_def']);
        $this->assertEquals(60, $result[1]['speed']);
        $this->assertEquals(1, $result[1]['generation']);
        $this->assertEquals('False', $result[1]['legendary']);
    }

    private function createTypeEntity(string $name): Type
    {
        $type = new Type();
        $type->setSlug($name);
        $type->setName($name);

        return $type;
    }
}
