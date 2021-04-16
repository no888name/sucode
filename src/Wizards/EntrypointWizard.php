<?php

namespace App\Wizards;

use App\Templates\Template;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class EntrypointWizard
{
    public static function run(InputInterface $input, OutputInterface $output)
    {
        $customPath = Helper::getCustomPath();
        $manifestPath = Helper::getManifestPath();

        $io = new SymfonyStyle($input, $output);
        $file = new File($io);

        if (!$customPath) {
            $io->writeln('manifest.php not found. Please run the command from the src directory');
            exit;
        }


        $iniqueKey = lcfirst($io->ask('Entry point name unique name'));


        //create language dir
        $dir = $file->mkdir("$customPath/Extension/application/Ext/EntryPointRegistry");
        $file->put_content("$dir/$iniqueKey.php", Template::renderEntrypoint([
            ':customEntryPoint' => $iniqueKey,
        ]));


        $dir = $file->mkdir("$customPath/entrypoints");
        $file->put_content("$dir/$iniqueKey.php", Template::renderEntrypointSample([
            ':customEntryPoint' => $iniqueKey,
        ]));

        $file->printSummary();
    }
}
