<?php

namespace App\Controller;

use App\Entity\Pokemon;
use App\Service\PokemonManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;

class UpdatePokemonController extends AbstractController
{
    public function __invoke(Pokemon $pokemon, PokemonManager $pokemonManager,Request $request, SerializerInterface $serializer): Pokemon
    {
        $data = json_decode($request->getContent(), true);
        $pokemonManager->updatePokemonType($data, $pokemon);

        return $pokemon;
    }
}
