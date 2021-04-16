<?php

namespace App\Wizards;


class File
{
    public $filesCreated = [];
    public $filesUpdated = [];
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

        if (file_exists($filename)) {
            file_put_contents($filename, $data);
            $this->filesUpdated[] = $filename;
        } else {
            file_put_contents($filename, $data);
            $this->filesCreated[] = $filename;
        }
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

        $created = count($this->filesCreated) || count($this->dirsProcessed);
        $updated = count($this->filesUpdated);

        if ($created) {
            $this->io->writeln('Files created:');
            foreach ($this->dirsProcessed as $dir) {
                $this->io->writeln($dir);
            }

            foreach ($this->filesCreated as $dir) {
                $this->io->writeln($dir);
            }
        }

        if ($updated) {
            $this->io->writeln('Files updated:');
            foreach ($this->filesUpdated as $dir) {
                $this->io->writeln($dir);
            }
        }


        $this->io->writeln('Finished.');
    }
}
