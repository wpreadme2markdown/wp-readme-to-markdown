<?php

namespace SunChaser\WP2MD\cli;

use SunChaser\WP2MD\Converter;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class Convert extends Command
{
    protected function configure()
    {
        $this->setName('convert');
        $this->setDescription('Convert WordPress Plugin readme file to Markdown');
        $this->addOption('input',  'i', InputOption::VALUE_REQUIRED, 'WordPress Plugin readme.txt');
        $this->addOption('output', 'o', InputOption::VALUE_REQUIRED, 'Markdown file');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var \Symfony\Component\Console\Helper\FormatterHelper $formatter */
        //$formatter = $this->getHelperSet()->get('formatter');

        $readme = $input->getOption('input');
        if ($readme === null) {
            $readme = 'php://stdin';
        } elseif (is_file($readme) === false || is_readable($readme) === false) {
            $output->writeln('<error>You should specify the readme file</error>');
        }

        $readmeData = file_get_contents($readme);

        $markdownData = Converter::Convert($readmeData);

        $markdown = $input->getOption('output');
        if ($markdown) {
            file_put_contents($markdown, $markdownData);
        } else {
            $output->writeln($markdownData, OutputInterface::OUTPUT_RAW);
        }
    }
} 
