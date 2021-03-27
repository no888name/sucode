<?php

namespace App\Wizards;

use color\Color;
use templates\Template;
use wizard\fields\FieldFactory;

class O2MRelWizard
{
    public static function run()
    {
        if (!is_file('manifest.php')) {
            Color::printLnColored('manifest.php not found. Please run the command from the src directory', 'red');
            die;
        }

        $lsName = Helper::askString('lhs_module: ');
        $lsSingular = Helper::askString('lhs singular module name ' . substr($lsName, 0, -1) . ': ');
        $lhsTable = Helper::askString('lhs_table: ');
        $lhsKey = Helper::askString('lhs_key: ');

        $rhsModule = Helper::askString('rhs_module: ');
        $rhsTable = Helper::askString('rhs_table: ');
        $rhsKey = Helper::askString('rhs_key: ');

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
        Helper::mkdir("custom/Extension/modules/$lsName/Ext/Vardefs/");
        file_put_contents("custom/Extension/modules/$lsName/Ext/Vardefs/" . strtolower($linkName) . '.php', $content);

        //write label translations
        Helper::mkdir("custom/Extension/modules/$lsName/Ext/Language/");
        $labelsData = [];
        $labelsData['label'] = FieldFactory::getLabelName($linkName);
        $labelsData['translation'] = $rhsModule . ' of ' . $lsName;
        file_put_contents("custom/Extension/modules/$lsName/Ext/Language/en_us." . strtolower($linkName) . '.php', Template::renderLabelsFile([$labelsData]));

        //ask about subpanel
        $subpanel = Helper::askString('Do you want to display subpanel?');
        if ('y' == $subpanel || 'yes' == $subpanel) {
            $subpanelName = strtolower($rhsModule) . '_subpanel';
            Helper::mkdir("custom/Extension/modules/$lsName/Ext/clients/base/layouts/subpanels");
            $content = Template::renderSubpanelFile([
                ':lhsName' => $lsName,
                ':label' => FieldFactory::getLabelName($subpanelName),
                ':linkName' => $linkName,
            ]);
            file_put_contents("custom/Extension/modules/$lsName/Ext/clients/base/layouts/subpanels/" . $subpanelName . '.php', $content);

            $labelsData = [];
            $labelsData['label'] = FieldFactory::getLabelName($subpanelName);
            $labelsData['translation'] = $rhsModule . ' of ' . $lsName;
            file_put_contents("custom/Extension/modules/$lsName/Ext/Language/en_us." . strtolower($subpanelName) . '.php', Template::renderLabelsFile([$labelsData]));
        }
    }
}
