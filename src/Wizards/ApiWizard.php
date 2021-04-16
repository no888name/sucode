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
        $file = new File($io);


        if (!$customPath) {
            $io->writeln('manifest.php not found. Please run the command from the src directory');
            exit;
        }
        //:template-layout-name
        $className = $io->ask('Enter API Class Name ', 'MyCrm' . ucfirst(Helper::generateRandomString(4)) . 'Api');

        //1 prepare layout files
        $file->mkdir("$customPath/clients/base/api");

        $content = Template::renderApi([
            'SampleApi' => $className,
            ':class_name' => strtolower($className),
        ]);
        $file->put_content("$customPath/clients/base/api/$className.php", $content);

        $file->mkdir("$customPath/jobs");
        $content = Template::renderCustomJobScheduler([]);
        $file->put_content("$customPath/clients/base/api/CustomJobScheduler.php", $content);

        $file->printSummary();
    }
}
