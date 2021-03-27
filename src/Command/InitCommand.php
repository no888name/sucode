<?php

namespace App\Command;

use App\Templates\Template;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class InitCommand extends Command
{
    // the name of the command (the part after "bin/console")
    protected static $defaultName = 'init';

    protected function configure()
    {
        $this
            // the short description shown while running "php bin/console list"
            ->setDescription('Create new package')

            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp('Initiates new SugarCRM installable package');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $result = $io->confirm('Do you want to create sugar package in current directory ?');
        $data = [];

        if ($result) {
            //do stuff
            $io->writeln('Creating ...');

            $name = $data[':name'] = $io->ask('Input package name : ', '', function ($name) {
                $name = str_replace('_', '-', $name);
                $name = str_replace(' ', '-', $name);

                return $name;
            });

            $data[':description'] = $io->ask('Input package description : ');
            $data[':date'] = date('Y-m-d H:i:s');

            $nameParts = explode('-', $data[':name']);

            $nameParts = array_map(function ($item) {
                return ucfirst($item);
            }, $nameParts);

            $data[':UpperCamelName'] = implode('', $nameParts);

            mkdir($name);
            mkdir("$name/src");
            mkdir("$name/src/custom");
            mkdir("$name/src/custom/Extension");
            mkdir("$name/src/custom/Extension/modules");
            mkdir("$name/src/custom/Extension/application");
            mkdir("$name/src/custom/Extension/application/Ext");
            mkdir("$name/src/custom/Extension/application/Ext/Language");
            mkdir("$name/src/scripts");

            $manifest = Template::renderManifest($data);
            file_put_contents($name . '/src/manifest.php', $manifest);
            file_put_contents($name . '/zipper.php', Template::renderZipper());
            file_put_contents($name . '/.php_cs', Template::renderPhpCs());
            file_put_contents($name . '/.gitignore', Template::renderGitignore());
            file_put_contents($name . '/src/LICENSE', Template::renderLicence());
            file_put_contents($name . '/src/scripts/post_install.php', Template::renderPostInstall());
        }

        $io->writeln('Finished ...');

        return Command::SUCCESS;
    }
}
