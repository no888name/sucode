<?php

namespace App\Command;

use App\Templates\Template;
use App\Wizards\ApiWizard;
use App\Wizards\FieldsWizard;
use App\Wizards\HooksWizard;
use App\Wizards\JsGroupingsWizard;
use App\Wizards\LayoutWizard;
use App\Wizards\ManyToManyRelWizard;
use App\Wizards\O2MRelWizard;
use App\Wizards\SchedulerWizard;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Style\SymfonyStyle;

class AddCommand extends Command
{
    // the name of the command (the part after "bin/console")
    protected static $defaultName = 'add';

    protected function configure()
    {
        $this
            // the short description shown while running "php bin/console list"
            ->setDescription('Add functionality')

            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp('Add new functionality to package');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $io = new SymfonyStyle($input, $output);
        $result = $io->writeln('');

        $helper = $this->getHelper('question');
        $question = new ChoiceQuestion(
            'Please select option',
            // choices can also be PHP objects that implement __toString() method
            [
                1 => 'Init hooks for module',
                2 => 'Create custom field',
                3 => 'Create one-to-many relationship',
                4 => 'Create many-to-many relationship',
                5 => 'Add script to JSGroupings',
                6 => 'Add admin section/layout',
                7 => 'Create Api Endpoint',
                8 => 'Add Scheduler',

                9 => 'Add field to filter[n/a]',
                10 => 'Add action menu[n/a]',
                11 => 'Create Bean wizard[n/a]',
            ],
            0
        );
        $question->setErrorMessage('Action %s is invalid.');

        $action = $helper->ask($input, $output, $question);
        $output->writeln('You have selected: ' . $action);

        switch ($action) {
            case 'Init hooks for module':
                //do hooks init
                HooksWizard::run($input, $output);
                break;
            case 'Create custom field':
                //do custom field addition
                FieldsWizard::run($input, $output);
                break;
            case 'Create one-to-many relationship':
                //do one-to-many relation
                O2MRelWizard::run($input, $output);
                break;
            case 'Create many-to-many relationship':
                //do many-to-many
                ManyToManyRelWizard::run($input, $output);
                break;
            case 'Add script to JSGroupings':
                //do many-to-many
                JsGroupingsWizard::run($input, $output);
                break;
            case 'Add admin section/layout':
                //do many-to-many
                LayoutWizard::run($input, $output);
                break;
            case 'Create Api Endpoint':
                //do many-to-many
                ApiWizard::run($input, $output);
                break;

            case 'Add Scheduler':
                //do many-to-many
               SchedulerWizard::run($input, $output);
                break;

                // case 7:
                //     //do many-to-many
                //     break;

             
            default:
                //do many-to-many
                return;
        }


        return Command::SUCCESS;
    }
}
