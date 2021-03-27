<?php

namespace App\Wizards;

use RecursiveCallbackFilterIterator;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

class DifferWizard
{
    public static $i = 0;

    public static function run()
    {
        $rootPath2 = '';
        if (is_file('manifest.php')) {
            $rootPath = 'custom';

            if (is_dir('SugarModules')) {
                $rootPath2 = 'SugarModules';
            } else if (is_dir('modules')) {
                $rootPath2 = 'modules';
            }
        } elseif (is_file('src/manifest.php')) {
            $rootPath = 'src/custom';

            if (is_dir('src/SugarModules')) {
                $rootPath2 = 'src/SugarModules';
            } else if (is_dir('src/modules')) {
                $rootPath2 = 'src/modules';
            }
        }

        $deploymentPath = null;
        if (is_file('.sucode')) {
            $cache = file_get_contents('.sucode');
            $config = json_decode($cache, true);
            $deploymentPath = $config['deploymentPath'];
        }

        $deploymentPath = Helper::askString('Where is you deployment folder', $deploymentPath);

        $config['deploymentPath'] = $deploymentPath;
        file_put_contents('.sucode', json_encode($config));
        $deploymentPath2 = $deploymentPath . '/modules';
        $deploymentPath = $deploymentPath . '/custom';

        // Will exclude everything under these directories
        $exclude = ['.git', 'otherDirToExclude', 'scripts', 'manifest.php'];

        self::specificRun($rootPath, $deploymentPath, $exclude);
        if($rootPath2) self::specificRun($rootPath2, $deploymentPath2, $exclude);
    }

    public static function specificRun($rootPath, $deploymentPath, $exclude)
    {
        $filter = function ($file, $key, $iterator) use ($exclude) {
            if ($iterator->hasChildren() && !in_array($file->getFilename(), $exclude)) {
                return true;
            }

            return $file->isFile();
        };

        $innerIterator = new RecursiveDirectoryIterator(
            $rootPath,
            RecursiveDirectoryIterator::SKIP_DOTS
        );

        $iterator = new RecursiveIteratorIterator(
            new RecursiveCallbackFilterIterator($innerIterator, $filter)
        );

        foreach ($iterator as $pathname => $file) {
            // Skip directories (they would be added automatically)
            if (!$file->isDir()) {
                // Get real and relative path for current file
                $filePath = $file->getRealPath();

                $relativePath = substr($pathname, strlen($rootPath) + 1);

                $fileOnDeploySide = $deploymentPath . '/' . $relativePath;

                $output = [];
                $status = 0;

                $command = 'diff --brief -N ' . $filePath . ' ' . $fileOnDeploySide;

                exec($command, $output, $status);
                if ($status) {
                    echo self::$i . ') ' . $relativePath . ' does not match' . PHP_EOL;
                    echo $command . PHP_EOL . PHP_EOL;
                    ++self::$i;
                }
            }
        }
    }
}
