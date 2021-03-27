<?php

namespace App\Wizards;

use App\Templates\Template;
use color\Color;
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

        if (!$customPath) {
            $io->writeln('manifest.php not found. Please run the command from the src directory');
            die;
        }

        $data = [];

        $moduleName = $io->ask('Module name');
        $hookUniqueKey = $io->ask('Hook unique name');

        $className = ucwords($moduleName) . ucwords($hookUniqueKey);
        $className = str_replace('_', '', $className);
        $hooksClassName = $className . 'Hooks';

        //create
        Helper::mkdir("$customPath/Extension/modules/$moduleName/Ext/LogicHooks/");
        file_put_contents("$customPath/Extension/modules/$moduleName/Ext/LogicHooks/" . strtolower($hookUniqueKey) . '_hooks.php', Template::renderHooks([':className' => $hooksClassName]));

        Helper::mkdir("$customPath/hooks");
        file_put_contents("$customPath/hooks/$className" . 'Hooks.php', Template::renderHooksClass([':className' => $hooksClassName]));

        //module helper
        $helper = Template::renderHelper($data);
        Helper::mkdir("$customPath/general");
        file_put_contents("$customPath/general/ModuleHelper.php", $helper);
    }

}
