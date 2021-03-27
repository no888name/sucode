<?php

namespace App\Wizards;

use App\Templates\Template;
use App\Wizards\fields\FieldFactory;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class O2MRelWizard
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

        $lsName = $io->ask('lhs_module');
        $lsSingular = $io->ask('lhs singular module name', substr($lsName, 0, -1));
        $lhsTable = $io->ask('lhs_table', strtolower($lsName));
        $lhsKey = $io->ask('lhs_key', 'id');

        $rhsModule = $io->ask('rhs_module');
        $rhsTable = $io->ask('rhs_table', strtolower($rhsModule));
        $rhsKey = $io->ask('rhs_key', strtolower(substr($rhsModule, 0, -1) . '_id'));

        $linkName = strtolower($lsName) . '_' . strtolower($rhsModule) . '_link';

        //write vardef
        $content = Template::renderOneToManyVardef([
            ':lhsSingular' => $lsSingular,
            ':linkName' => $linkName,
            ':relationshipName' => strtolower($lsName) . '_' . strtolower($rhsModule) . '_rel',
            ':label' => FieldFactory::getLabelName($linkName),
            ':rhsModule' => $rhsModule,
            ':lhsName' => $lsName,
            ':lhsTable' => $lhsTable,
            ':lhsKey' => $lhsKey,
            ':rhsTable' => $rhsTable,
            ':rhsKey' => $rhsKey,
        ]);
        Helper::mkdir("$customPath/Extension/modules/$lsName/Ext/Vardefs/");
        file_put_contents("$customPath/Extension/modules/$lsName/Ext/Vardefs/" . strtolower($linkName) . '.php', $content);

        //write label translations
        Helper::mkdir("$customPath/Extension/modules/$lsName/Ext/Language/");
        $labelsData = [];
        $labelsData['label'] = FieldFactory::getLabelName($linkName);
        $labelsData['translation'] = $rhsModule . ' of ' . $lsName;
        file_put_contents("$customPath/Extension/modules/$lsName/Ext/Language/en_us." . strtolower($linkName) . '.php', Template::renderLabelsFile([$labelsData]));

        //ask about subpanel
        $subpanel = $io->ask('Do you want to display subpanel?', 'y');
        if ('y' == $subpanel || 'yes' == $subpanel) {
            $subpanelName = strtolower($rhsModule) . '_subpanel';
            Helper::mkdir("$customPath/Extension/modules/$lsName/Ext/clients/base/layouts/subpanels");
            $content = Template::renderSubpanelFile([
                ':lhsName' => $lsName,
                ':label' => FieldFactory::getLabelName($subpanelName),
                ':linkName' => $linkName,
            ]);
            file_put_contents("$customPath/Extension/modules/$lsName/Ext/clients/base/layouts/subpanels/" . $subpanelName . '.php', $content);

            $labelsData = [];
            $labelsData['label'] = FieldFactory::getLabelName($subpanelName);
            $labelsData['translation'] = $rhsModule . ' of ' . $lsName;
            file_put_contents("$customPath/Extension/modules/$lsName/Ext/Language/en_us." . strtolower($subpanelName) . '.php', Template::renderLabelsFile([$labelsData]));
        }
    }
}
