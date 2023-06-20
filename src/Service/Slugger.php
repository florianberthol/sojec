<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

class Slugger
{
    public function __construct(private SluggerInterface $slugger)
    {}

    public function slug(string $str): string
    {
        return $this->slugger->slug($str)->folded()->toString();
    }
}