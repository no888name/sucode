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
        $file = new File($io);


        if (!$customPath) {
            $io->writeln('manifest.php not found. Please run the command from the src directory');
            exit;
        }

        $aclName = $io->ask('Input Acl name', 'CustomAclBlaBla');
        $file->mkdir("$customPath/data/acl");
        $file->put_content("$customPath/data/acl/$aclName.php", Template::renderAcl([
            'AclTemplateName' => $aclName,
        ]));

        $file->mkdir("$customPath/Extension/application/Ext/Include/");
        $file->put_content("$customPath/Extension/application/Ext/Include/$aclName.php", Template::renderIncludeAcl([
            'aclName' => $aclName,
        ]));

        $moduleName = $io->ask('For which module should I enable Acl? ', 'Accounts');
        $moduleNameSingular = $io->ask('module name singular ', substr($moduleName, 0, -1));

        $file->mkdir("$customPath/Extension/modules/$moduleName/Ext/Vardefs/");
        $file->put_content("$customPath/Extension/modules/$moduleName/Ext/Vardefs//$aclName.php", Template::renderAclEnable([
            'moduleName' => $moduleName,
            'moduleNameSingular' => $moduleNameSingular,
            'aclName' => $aclName,
        ]));

        $file->printSummary();
    }
}
