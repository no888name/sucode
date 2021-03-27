<?php

namespace App\Wizards;

use color\Color;
use RecursiveCallbackFilterIterator;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use templates\Template;

class AnonWizard
{
    public static $i = 0;

    public static function run()
    {
       //get db credentials
        if (is_file('config.php')) {
            file_put_contents('anon_script.php', Template::renderAnonScript([]));
            file_put_contents('Faker.php', Template::renderFaker([]));
        } else {
            Color::printLnColored('config not found', 'red');
            die;
        }

        echo 'anon_script.php and Faker.php successfully deployed'.PHP_EOL;

        //create bacup


        //do contacts

        //do accounts
        //do opportunities

        //do leads



    }
}