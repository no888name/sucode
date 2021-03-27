<?php

namespace App\Wizards;

use App\Templates\Template;
use App\Wizards\fields\FieldFactory;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ManyToManyRelWizard
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

        $lhsModule = $io->ask('lhs_module');
        $lsSingular = $io->ask('lhs singular module name', substr($lhsModule, 0, -1));
        $lhsTable = $io->ask('lhs_table', strtolower($lhsModule));
        $lhsKey = $io->ask('lhs_key', 'id');

        $rhsModule = $io->ask('rhs_module');
        $rsSingular = $io->ask('lhs singular module name ', substr($rhsModule, 0, -1));
        $rhsTable = $io->ask('rhs_table', strtolower($rhsModule));
        $rhsKey = $io->ask('rhs_key', 'id');

        $lower_case_rel_name = $lhsTable . '_' . $rhsTable . '_rel';
        $linkName = $lhsTable . '_' . $rhsTable . '_link';

        $join_key_lhs = $io->ask('join_key_lhs', $lhsTable . '_id');
        $join_key_rhs = $io->ask('join_key_rhs ', $rhsTable . '_id');

        $module1_module2 = strtolower($lhsModule) . '_' . strtolower($rhsModule);

        //1 saving link definition for left module
        $content = Template::renderManyToManyVardef([
            ':Singular' => $lsSingular,
            ':linkName' => $linkName,
            ':lower_case_rel_name' => $lower_case_rel_name,
            ':label' => FieldFactory::getLabelName($linkName),
            ':Module' => $rhsModule,
            ':join_key' => $join_key_rhs,
        ]);

        Helper::mkdir("custom/Extension/modules/$lhsModule/Ext/Vardefs/");
        Helper::mkdir("custom/Extension/modules/$lhsModule/Ext/Language/");

        file_put_contents("custom/Extension/modules/$lhsModule/Ext/Vardefs/" . strtolower($linkName) . '_M2M.php', $content);

        //2 saving link definition for right module
        $content = Template::renderManyToManyVardef([
            ':Singular' => $rsSingular,
            ':linkName' => $linkName,
            ':lower_case_rel_name' => $lower_case_rel_name,
            ':label' => FieldFactory::getLabelName($linkName),
            ':Module' => $lhsModule,
            ':join_key' => $join_key_lhs,
        ]);
        Helper::mkdir("custom/Extension/modules/$rhsModule/Ext/Vardefs/");
        Helper::mkdir("custom/Extension/modules/$rhsModule/Ext/Language/");

        file_put_contents("custom/Extension/modules/$rhsModule/Ext/Vardefs/" . strtolower($linkName) . '_M2M.php', $content);

        //3  write M2M custom relationship metadata
        $content = Template::renderManyToManyMetadata([
            ':lower_case_rel_name' => $lower_case_rel_name,
            ':lhsModule' => $lhsModule,
            ':lhsTable' => $lhsTable,
            ':lhsKey' => $lhsKey,
            ':rhsModule' => $rhsModule,
            ':rhsTable' => $rhsTable,
            ':rhsKey' => $rhsKey,
            ':join_key_lhs' => $join_key_lhs,
            ':join_key_rhs' => $join_key_rhs,
        ]);

        Helper::mkdir('custom/metadata/');
        file_put_contents("custom/metadata/{$module1_module2}MetaData.php", $content);

        //4  write to application directory file that included metadata above
        $content = Template::renderTableDictionary([
            ':module1_module2' => $module1_module2,
        ]);
        Helper::mkdir('custom/Extension/application/Ext/TableDictionary');
        file_put_contents("custom/Extension/application/Ext/TableDictionary/{$module1_module2}.php", $content);

        //5 subpanel to left hand module
        $subpanelName = strtolower($rhsModule) . '_subpanel';
        Helper::mkdir("custom/Extension/modules/$lhsModule/Ext/clients/base/layouts/subpanels");
        $content = Template::renderSubpanelFile([
            ':lhsName' => $lhsModule,
            ':label' => FieldFactory::getLabelName($subpanelName),
            ':linkName' => $linkName,
        ]);
        file_put_contents("custom/Extension/modules/$lhsModule/Ext/clients/base/layouts/subpanels/" . $subpanelName . '.php', $content);
        //5.1 Subpanel Label
        $labelsData = [];
        $labelsData['label'] = FieldFactory::getLabelName($subpanelName);
        $labelsData['translation'] = $rhsModule . ' of ' . $lhsModule;
        file_put_contents("custom/Extension/modules/$lhsModule/Ext/Language/en_us." . strtolower($subpanelName) . '.php', Template::renderLabelsFile([$labelsData]));

        //6 subpanel to right hand module
        $subpanelName = strtolower($lhsModule) . '_subpanel';
        Helper::mkdir("custom/Extension/modules/$rhsModule/Ext/clients/base/layouts/subpanels");
        $content = Template::renderSubpanelFile([
            ':lhsName' => $rhsModule,
            ':label' => FieldFactory::getLabelName($subpanelName),
            ':linkName' => $linkName,
        ]);
        file_put_contents("custom/Extension/modules/$rhsModule/Ext/clients/base/layouts/subpanels/" . $subpanelName . '.php', $content);

        //6.1 Subpanel Label
        $labelsData = [];
        $labelsData['label'] = FieldFactory::getLabelName($subpanelName);
        $labelsData['translation'] = $rhsModule . ' of ' . $lhsModule;
        file_put_contents("custom/Extension/modules/$lhsModule/Ext/Language/en_us." . strtolower($subpanelName) . '.php', Template::renderLabelsFile([$labelsData]));

        //write label translations (left side)

        $labelsData = [];
        $labelsData['label'] = FieldFactory::getLabelName($linkName);
        $labelsData['translation'] = $rhsModule . ' of ' . $lhsModule;
        file_put_contents("custom/Extension/modules/$lhsModule/Ext/Language/en_us." . strtolower($linkName) . '.php', Template::renderLabelsFile([$labelsData]));

        //write label translations (right side)
        $labelsData = [];
        $labelsData['label'] = FieldFactory::getLabelName($linkName);
        $labelsData['translation'] = $lhsModule . ' of ' . $rhsModule;
        file_put_contents("custom/Extension/modules/$rhsModule/Ext/Language/en_us." . strtolower($linkName) . '.php', Template::renderLabelsFile([$labelsData]));
    }
}
