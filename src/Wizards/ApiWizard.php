<?php

namespace App\Wizards;

use color\Color;
use templates\Template;

class ApiWizard
{
    public static function run()
    {
        if (!is_file('manifest.php')) {
            Color::printLnColored('manifest.php not found. Please run the command from the src directory', 'red');
            die;
        }

        //:template-layout-name
        $className = Helper::askString('Enter API Class Name ', 'MyCrm' . ucfirst(Helper::generateRandomString(4)) . 'Api');

        //1 prepare layout files
        Helper::mkdir('custom/clients/base/api');

        $content = Template::renderApi([
            'SampleApi' => $className,
            ':class_name' => strtolower($className),
        ]);
        file_put_contents("custom/clients/base/api/$className.php", $content);

        Helper::mkdir('custom/jobs');
        $content = Template::renderCustomJobScheduler([]);
        file_put_contents('custom/clients/base/api/CustomJobScheduler.php', $content);
    }
}
