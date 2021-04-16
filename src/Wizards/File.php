<?php

namespace App\Wizards;


class File
{
    public $filesProcessed = [];
    public $dirsProcessed = [];

    /**
     * symfony input 
     *
     * @var SymfonyStyle
     */
    private $io;

    public function __construct($io)
    {
        $this->io = $io;
    }

    public function put_content($filename, $data)
    {

        file_put_contents($filename, $data);

        $this->filesProcessed[] = $filename;
    }

    public function mkdir($dir)
    {

        if (false === strpos($dir, 'src')) {
            $dir = 'src/' . $dir;
        }

        if (!file_exists($dir)) {
            mkdir($dir, 0777, true);
            $this->dirsProcessed[] = $dir;
        }
       

        return $dir;
    }


    public function printSummary()
    {

        $this->io->writeln('Files created:');

        foreach ($this->dirsProcessed as $dir) {
            $this->io->writeln($dir);
        }

        foreach ($this->filesProcessed as $dir) {
            $this->io->writeln($dir);
        }
    }
}
