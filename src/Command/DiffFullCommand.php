<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class DiffFullCommand extends Command
{
    // the name of the command (the part after "bin/console")
    protected static $defaultName = 'diff-full';

    protected function configure()
    {
        $this
            // the short description shown while running "php bin/console list"
            ->setDescription('Show difference and apply changes')

            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp('Show difference and apply changes');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $result = $io->confirm('Do you want to create sugar package in current directory ?');

        return Command::SUCCESS;
    }
}
