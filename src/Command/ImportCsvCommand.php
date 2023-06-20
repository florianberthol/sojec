<?php

namespace App\Command;

use App\Service\PokemonManager;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:import:csv',
    description: 'Import a pokemon list in database',
)]
class ImportCsvCommand extends Command
{
    public function __construct(private PokemonManager $pokemonManager, string $name = null)
    {
        parent::__construct($name);
    }


    protected function configure(): void
    {
        $this->addArgument('filePath', InputArgument::REQUIRED, 'input file (CSV)');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $filePath = $input->getArgument('filePath');
        $this->pokemonManager->createPokemonFromCSV($filePath);

        $io->success('Pokemon imported');

        return Command::SUCCESS;
    }
}
