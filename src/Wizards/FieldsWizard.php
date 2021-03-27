<?php

namespace App\Wizards;

use App\Templates\Template;
use App\Wizards\fields\FieldFactory;
use color\Color;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class FieldsWizard
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

        $moduleName = $io->ask('Input Module name');
        $fieldName = $io->ask('Input field name');
        $fieldType = $io->ask('Input field type: string,enum,int','string');
        $fieldLabel = $io->ask('Input field label');

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
