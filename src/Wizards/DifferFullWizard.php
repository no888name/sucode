<?php

namespace App\Wizards;

use color\Color;
use RecursiveCallbackFilterIterator;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

class DifferFullWizard
{

    public static $i = 0;

    public static function run(InputInterface $input, OutputInterface $output)
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
                $dirPath = $file->getPath();

                $relativeFilePath = substr($pathname, strlen($rootPath) + 1);
                $relativeDirPath = $deploymentPath . '/' . substr($dirPath, strlen($rootPath) + 1);

                $fileOnDeploySide = $deploymentPath . '/' . $relativeFilePath;

                $output = [];
                $status = 0;

                $command = 'diff ' . $filePath . ' ' . $fileOnDeploySide;

                exec($command, $output, $status);
                if ($status) {

                    $res = implode(PHP_EOL, $output);
                    echo $res . PHP_EOL;

                    Color::printLnColored('->' . $relativeFilePath . ' does not match', 'cyan');

                    $choice = Helper::askString('1 - accept yours, 2 -accept destination, 3 - skipp', '3');

                    switch ($choice) {
                        case '1':
                            //copy my to deployment
                            $cmd = sprintf('mkdir -p "%s" && cp "%s" "%s"',$relativeDirPath, $filePath,$fileOnDeploySide);
                            echo 'cmd '.$cmd.PHP_EOL;
                            exec($cmd);
                            break;
                        case '2':

                            //copy from deployment to me
                            copy($fileOnDeploySide, $filePath);
                            break;

                        case '3':
                            //copy my to deployment
                            echo 'skipped' . PHP_EOL;
                            break;
                    }


                    ++self::$i;
                }
            }
        }
    }
}
