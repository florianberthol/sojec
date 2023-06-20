<?php

namespace App\Tests;

use App\Service\PokemonManager;
use App\Service\Slugger;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\String\AbstractUnicodeString;
use Symfony\Component\String\Slugger\SluggerInterface;

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
}
