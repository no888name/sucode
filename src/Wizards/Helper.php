<?php

namespace App\Wizards;

use color\Color;

class Helper
{
    public static function mkdir($dir)
    {
        if (false === strpos($dir, 'src')) {
            $dir = 'src/' . $dir;
        }

        if (!file_exists($dir)) {
            mkdir($dir, 0777, true);
        }
    }

    public static function var_export_short($data, $return = true)
    {
        $dump = var_export($data, true);

        $dump = preg_replace('#(?:\A|\n)([ ]*)array \(#i', '[', $dump); // Starts
        $dump = preg_replace('#\n([ ]*)\),#', "\n$1],", $dump); // Ends
        $dump = preg_replace('#=> \[\n\s+\],\n#', "=> [],\n", $dump); // Empties

        if ('object' == gettype($data)) { // Deal with object states
            $dump = str_replace('__set_state(array(', '__set_state([', $dump);
            $dump = preg_replace('#\)\)$#', '])', $dump);
        } else {
            $dump = preg_replace('#\)$#', ']', $dump);
        }

        if (true === $return) {
            return $dump;
        } else {
            echo $dump;
        }
    }

    public static function askString($prompt, $default = null, $color = 'cyan')
    {
        while (true) {
            if ($default) {
                $prompt = $prompt . ' [' . $default . '] : ';
            } else {
                $prompt = $prompt . ' : ';
            }

            $prompt = Color::printColored($prompt, $color);

            $line = rtrim(fgets(STDIN), "\n");

            if (!$line && $default) {
                $line = $default;
            }

            if (trim($line)) {
                return $line;
            }
        }
    }

    public static function generateRandomString($length = 10)
    {
        $characters = 'abcdefghijklmnopqrstuvwxyz';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; ++$i) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }

        return $randomString;
    }

    public static function getCustomPath()
    {
        $rootPath = '';
        if (is_file('manifest.php')) {
            $rootPath = 'custom';
        } elseif (is_file('src/manifest.php')) {
            $rootPath = 'src/custom';
        }

        return $rootPath;
    }

    public static function getManifestPath()
    {
        $rootPath = '';
        if (is_file('manifest.php')) {
            $rootPath = 'manifest.php';
        } elseif (is_file('src/manifest.php')) {
            $rootPath = 'src/manifest.php';
        }

        return $rootPath;
    }
}
