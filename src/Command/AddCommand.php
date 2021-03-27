<?php

namespace App\Command;

use App\Templates\Template;
use App\Wizards\HooksWizard;
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
                'Init hooks for module',
                'Create custom field',
                'Create one-to-many relationship',
                'Create many-to-many relationship',
                'Add script to JSGroupings',
                'Add field to filter[n/a]',
                'Add action menu[n/a]',
                'Create Bean wizard[n/a]',
                'Add admin section/layout',
                'Create Api Endpoint',
                'Add Scheduler'
            ],
            0
        );
        $question->setErrorMessage('Color %s is invalid.');

        $action = $helper->ask($input, $output, $question);
        $output->writeln('You have selected: ' . $action);

        switch ($action) {
            case 0:
                //do hooks init
                HooksWizard::run($input,$output);
                break;
            // case 2:
            //     //do custom field addition
            //     \wizard\FieldsWizard::run();
            //     break;
            // case 3:
            //     //do one-to-many relation
            //     \wizard\O2MRelWizard::run();
            //     break;
            // case 4:
            //     //do many-to-many
            //     \wizard\ManyToManyRelWizard::run();
            //     break;
            // case 5:
            //     //do many-to-many
            //     \wizard\JsGroupingsWizard::run();
            //     break;

            // case 7:
            //     //do many-to-many
            //     break;
            // case 9:
            //     //do many-to-many
            //     \wizard\LayoutWizard::run();
            //     break;
            // case 10:
            //     //do many-to-many
            //     \wizard\ApiWizard::run();
            //     break;

            //     case 11:
            //         //do many-to-many
            //         \wizard\SchedulerWizard::run();
            //         break;

            default:
                //do many-to-many
                return;
        }


        return Command::SUCCESS;
    }
}
