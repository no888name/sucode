<?php

namespace App\Wizards;

use color\Color;
use templates\Template;
use wizard\fields\FieldFactory;

class FieldsWizard
{
    public static function run()
    {
        $customPath = Helper::getCustomPath();
        $manifestPath = Helper::getManifestPath();

        if (!$customPath) {
            Color::printLnColored('manifest.php not found. Please run the command from the src directory', 'red');
            die;
        }

        $moduleName = Helper::askString('Input Module name');
        $fieldName = Helper::askString('Input field name');
        $fieldType = Helper::askString('Input field type: string,enum,int','string');
        $fieldLabel = Helper::askString('Input field label');

        //open manifest
        include $manifestPath;

        //prepare field
        $factory = new FieldFactory($fieldType, $fieldName, $moduleName);
        $data = $factory->process();
//        var_dump($data);
        $installdefs['custom_fields'][] = $data;
        $manifest['sucode'] = true;

        //write manifest
        $content = Template::renderReadyManifest($installdefs, $manifest);
        file_put_contents($manifestPath, $content);

        //write label translations
        Helper::mkdir($customPath."/Extension/modules/$moduleName/Ext/Language/");
        $labelsData = [];
        $labelsData['label'] = FieldFactory::getLabelName($fieldName);
        $labelsData['translation'] = $fieldLabel;
        file_put_contents($customPath."/Extension/modules/$moduleName/Ext/Language/en_us." . strtolower($fieldName) . '.lang.php', Template::renderLabelsFile([$labelsData]));

        if ($factory->listName) {
            //also need prepare file for list definition
            Helper::mkdir($customPath.'/Extension/application/Ext/Language/');
            file_put_contents($customPath.'/Extension/application/Ext/Language/en_us.' . $factory->listName . '_sucode.php', Template::renderListFile($factory->listName, []));
        }
    }
}
