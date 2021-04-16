<?php

namespace App\Wizards;

use App\Templates\Template;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class HooksWizard
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

        $data = [];

        $moduleName = $io->ask('Module name');
        $hookUniqueKey = $io->ask('Hook unique name');

        $className = ucwords($moduleName) . ucwords($hookUniqueKey);
        $className = str_replace('_', '', $className);
        $hooksClassName = $className . 'Hooks';

        //create
        $file->mkdir("$customPath/Extension/modules/$moduleName/Ext/LogicHooks/");
        $file->put_content("$customPath/Extension/modules/$moduleName/Ext/LogicHooks/" . strtolower($hookUniqueKey) . '_hooks.php', Template::renderHooks([':className' => $hooksClassName]));

        $file->mkdir("$customPath/hooks");
        $file->put_content("$customPath/hooks/$className" . 'Hooks.php', Template::renderHooksClass([':className' => $hooksClassName]));

        //module helper
        $helper = Template::renderHelper($data);
        $file->mkdir("$customPath/general");
        $file->put_content("$customPath/general/ModuleHelper.php", $helper);

        $file->printSummary();
    }
}
