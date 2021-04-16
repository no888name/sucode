<?php

namespace App\Wizards;

use App\Templates\Template;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class SchedulerWizard
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

        $moduleName = 'Schedulers';

        $iniqueKey = $io->ask('Scheduler unique name');

        $description = $io->ask('Scheduler description');

        $className = ucwords($moduleName) . ucwords($iniqueKey);
        $className = str_replace('_', '', $className);

        //create language dir
        $file->mkdir("$customPath/Extension/modules/$moduleName/Ext/Language/");
        $file->put_content("$customPath/Extension/modules/$moduleName/Ext/Language/en_us." . strtolower($iniqueKey) . '_scheduler.php', Template::renderSchedulerLang([
            ':LBL_LABEL_NAME' => 'LBL_' . strtoupper($iniqueKey) . '_SCHEDULER',
            ':LBL_LABEL_VALUE' => $description,
        ]));

        $file->mkdir("$customPath/Extension/modules/$moduleName/Ext/ScheduledTasks/");
        $file->put_content("$customPath/Extension/modules/$moduleName/Ext/ScheduledTasks/" . strtolower($iniqueKey) . '_scheduler.php', Template::renderSchedulerFile([':unique_name' => $iniqueKey]));
    
        $file->printSummary();
    }
}
