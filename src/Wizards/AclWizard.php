<?php

namespace App\Wizards;

use App\Templates\Template;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class AclWizard
{
    public static function run(InputInterface $input, OutputInterface $output)
    {
        $customPath = Helper::getCustomPath();
        $manifestPath = Helper::getManifestPath();

        $io = new SymfonyStyle($input, $output);

        if (!$customPath) {
            $io->writeln('manifest.php not found. Please run the command from the src directory');
            exit;
        }

        $aclName = $io->ask('Input Acl name', 'CustomAclBlaBla');
        Helper::mkdir("$customPath/data/acl");
        file_put_contents("$customPath/data/acl/$aclName.php", Template::renderAcl([
            'AclTemplateName' => $aclName,
        ]));

        Helper::mkdir("$customPath/Extension/application/Ext/Include/");
        file_put_contents("$customPath/Extension/application/Ext/Include/$aclName.php", Template::renderIncludeAcl([
            'aclName' => $aclName,
        ]));

        $moduleName = $io->ask('For which module should I enable Acl? ', 'Accounts');
        $moduleNameSingular = $io->ask('module name singular ', substr($moduleName, 0, -1));

        Helper::mkdir("$customPath/Extension/modules/$moduleName/Ext/Vardefs/");
        file_put_contents("$customPath/Extension/modules/$moduleName/Ext/Vardefs//$aclName.php", Template::renderAclEnable([
            'moduleName' => $moduleName,
            'moduleNameSingular' => $moduleNameSingular,
            'aclName' => $aclName,
        ]));
    }
}
