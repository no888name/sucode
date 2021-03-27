<?php

namespace App\Wizards;

use color\Color;
use templates\Template;

class JsGroupingsWizard
{
    public static function run()
    {
        if (!is_file('manifest.php')) {
            Color::printLnColored('manifest.php not found. Please run the command from the src directory', 'red');
            die;
        }

        $fileName = Helper::askString('Groupings file name');

        $grouppingHandlerNames = explode('_', $fileName);
        $handlerName = implode(array_map(function ($item) {
            return ucfirst($item);
        }, $grouppingHandlerNames));
        $grouppingHandlerName = Helper::askString('Groupings handler name', $handlerName . 'Handler');

        //1 php file definition
        $content = Template::renderJsGroupingsPhp([
            ':fileName' => strtolower($fileName),
        ]);
        Helper::mkdir('custom/Extension/application/Ext/JSGroupings/');
        file_put_contents('custom/Extension/application/Ext/JSGroupings/' . strtolower($fileName) . '.php', $content);

        //1 js file definition
        $content = Template::renderJsGroupingsJs([
            'jsGroupingHandler' => $grouppingHandlerName,
        ]);
        Helper::mkdir('custom/include/');
        file_put_contents('custom/include/' . strtolower($fileName) . '.js', $content);
    }
}
