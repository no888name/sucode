<?php

namespace App\Command;

use RecursiveCallbackFilterIterator;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Style\SymfonyStyle;

class DiffFullCommand extends Command
{
    // the name of the command (the part after "bin/console")
    protected static $defaultName = 'diff-full';

    protected $io = null;
    protected $input = null;
    protected $output = null;

    public static $i = 0;

    protected function configure()
    {
        $this
            // the short description shown while running "php bin/console list"
            ->setDescription('Show difference and apply changes')

            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp('Show difference and apply changes');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $this->io = $io;
        $this->input = $input;
        $this->output = $output;

        $rootPath2 = '';
        if (is_file('manifest.php')) {
            $rootPath = 'custom';

            if (is_dir('SugarModules')) {
                $rootPath2 = 'SugarModules';
            } elseif (is_dir('modules')) {
                $rootPath2 = 'modules';
            }
        } elseif (is_file('src/manifest.php')) {
            $rootPath = 'src/custom';

            if (is_dir('src/SugarModules')) {
                $rootPath2 = 'src/SugarModules';
            } elseif (is_dir('src/modules')) {
                $rootPath2 = 'src/modules';
            }
        }

        $deploymentPath = null;
        if (is_file('.sucode')) {
            $cache = file_get_contents('.sucode');
            $config = json_decode($cache, true);
            $deploymentPath = $config['deploymentPath'];
        }

        $deploymentPath = $io->ask('Where is you deployment folder', $deploymentPath);

        if ('src/modules' == $rootPath2) {
            $deploymentPath2 = $deploymentPath . '/modules';
        }
        if ('src/SugarModules' == $rootPath2) {
            $deploymentPath2 = $deploymentPath . '';
        }

        $config['deploymentPath'] = $deploymentPath;
        file_put_contents('.sucode', json_encode($config));
        $deploymentPath2 = $deploymentPath . '/modules';
        $deploymentPath = $deploymentPath . '/custom';

        // Will exclude everything under these directories
        $exclude = ['.git', 'otherDirToExclude', 'scripts', 'manifest.php'];

        $this->specificRun($rootPath, $deploymentPath, $exclude);
        if ($rootPath2) {
            $this->specificRun($rootPath2, $deploymentPath, $exclude);
        }

        return Command::SUCCESS;
    }

    public function specificRun($rootPath, $deploymentPath, $exclude)
    {
        $helper = $this->getHelper('question');

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

                    $question = new ChoiceQuestion(
                        '->' . $relativeFilePath . ' does not match',
                        // choices can also be PHP objects that implement __toString() method
                        [1 => 'accept yours', 2 => 'accept destination', 3 => 'skip'],
                        3
                    );

                    $choice = $helper->ask($this->input, $this->output, $question);

                    switch ($choice) {
                        case 'accept yours':
                            //copy my to deployment
                            $cmd = sprintf('mkdir -p "%s" && cp "%s" "%s"', $relativeDirPath, $filePath, $fileOnDeploySide);
                            echo 'cmd ' . $cmd . PHP_EOL;
                            exec($cmd);
                            break;
                        case 'accept destination':
                            //copy from deployment to me
                            copy($fileOnDeploySide, $filePath);
                            break;

                        case 'skip':
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
