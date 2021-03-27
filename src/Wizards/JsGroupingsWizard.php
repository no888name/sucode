<?php

namespace App\Wizards;

use App\Templates\Template;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class JsGroupingsWizard
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

        $fileName = $io->ask('Groupings file name');

        $grouppingHandlerNames = explode('_', $fileName);
        $handlerName = implode(array_map(function ($item) {
            return ucfirst($item);
        }, $grouppingHandlerNames));
        $grouppingHandlerName = $io->ask('Groupings handler name', $handlerName . 'Handler');

        //1 php file definition
        $content = Template::renderJsGroupingsPhp([
            ':fileName' => strtolower($fileName),
        ]);
        Helper::mkdir('custom/Extension/application/Ext/JSGroupings/');
        file_put_contents($customPath.'/Extension/application/Ext/JSGroupings/' . strtolower($fileName) . '.php', $content);

        //1 js file definition
        $content = Template::renderJsGroupingsJs([
            'jsGroupingHandler' => $grouppingHandlerName,
        ]);
        Helper::mkdir('custom/include/');
        file_put_contents($customPath.'/include/' . strtolower($fileName) . '.js', $content);
    }
}
