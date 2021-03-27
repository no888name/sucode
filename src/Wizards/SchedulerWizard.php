<?php

namespace App\Wizards;

use color\Color;
use templates\Template;

class SchedulerWizard
{
    public static function run()
    {
        $customPath = Helper::getCustomPath();
        $manifestPath = Helper::getManifestPath();

        if (!$customPath) {
            Color::printLnColored('manifest.php not found. Please run the command from the src directory', 'red');
            die;
        }
        $data = [];

        $moduleName = 'Schedulers';

        $iniqueKey = self::askUniqueName();
        $description = self::ask('Scheduler description');

        $className = ucwords($moduleName) . ucwords($iniqueKey);
        $className = str_replace('_', '', $className);
        $hooksClassName = $className . 'Hooks';

        //create language dir
        Helper::mkdir("$customPath/Extension/modules/$moduleName/Ext/Language/");
        file_put_contents("$customPath/Extension/modules/$moduleName/Ext/Language/en_us." . strtolower($iniqueKey).'_scheduler.php', Template::renderSchedulerLang([
            ':LBL_LABEL_NAME' => 'LBL_' . strtoupper($iniqueKey).'_SCHEDULER',
            ':LBL_LABEL_VALUE' => $description,
        ]));

        Helper::mkdir("$customPath/Extension/modules/$moduleName/Ext/ScheduledTasks/");
        file_put_contents("$customPath/Extension/modules/$moduleName/Ext/ScheduledTasks/". strtolower($iniqueKey)."_scheduler.php", Template::renderSchedulerFile([':unique_name' => $iniqueKey]));

       
    }

    public static function ask($prompt)
    {
        while (true) :
            Color::printColored($prompt . ' : ', 'cyan');
            $handle = fopen('php://stdin', 'r');
            $line = trim(fgets($handle));
            if (trim($line)) {
                return $line;
            }
        endwhile;
    }

    public static function askUniqueName()
    {
        while (true) :
            Color::printColored('Scheduler unique name : ', 'cyan');
            $handle = fopen('php://stdin', 'r');
            $line = trim(fgets($handle));
            if (trim($line)) {
                return $line;
            }
        endwhile;
    }

    public static function askUniqueDescription()
    {
        while (true) :
            Color::printColored('Scheduler description', 'cyan');
            $handle = fopen('php://stdin', 'r');
            $line = trim(fgets($handle));
            if (trim($line)) {
                return $line;
            }
        endwhile;
    }
}
