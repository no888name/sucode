<?php

namespace App\Wizards;

use App\Templates\Template;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ApiWizard
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
        //:template-layout-name
        $className = $io->ask('Enter API Class Name ', 'MyCrm' . ucfirst(Helper::generateRandomString(4)) . 'Api');

        //1 prepare layout files
        Helper::mkdir('custom/clients/base/api');

        $content = Template::renderApi([
            'SampleApi' => $className,
            ':class_name' => strtolower($className),
        ]);
        file_put_contents("$customPath/clients/base/api/$className.php", $content);

        Helper::mkdir('custom/jobs');
        $content = Template::renderCustomJobScheduler([]);
        file_put_contents("$customPath/clients/base/api/CustomJobScheduler.php", $content);
    }
}
