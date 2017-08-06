<?php
/**
 * @author Christian Archer <sunchaser@sunchaser.info>
 * @copyright © 2014, Christian Archer
 * @license MIT
 */

namespace WPReadme2Markdown\Cli;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\RuntimeException;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use WPReadme2Markdown\Converter;

class Convert extends Command
{
    protected function configure()
    {
        $this->setName('wp2md');
        $this->setDescription('Converts WordPress Plugin Readme files to GitHub Flavored Markdown');

        $this->addArgument('input',         InputArgument::OPTIONAL,        'WordPress Plugin readme.txt');
        $this->addArgument('output',        InputArgument::OPTIONAL,        'Markdown file');

        $this->addOption('input',   'i',    InputOption::VALUE_REQUIRED,    'WordPress Plugin readme.txt');
        $this->addOption('output',  'o',    InputOption::VALUE_REQUIRED,    'Markdown file');
        $this->addOption('slug',    's',    InputOption::VALUE_REQUIRED,    'Plugin slug');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $readme = $input->getOption('input') ?: $input->getArgument('input');
        if ($readme === null) {
            $readme = 'php://stdin';
        } else {
            $readme = realpath($readme);

            if (is_file($readme) === false || is_readable($readme) === false) {
                throw new RuntimeException('You should specify a readable readme file');
            }
        }

        $readmeData = file_get_contents($readme);

        $markdownData = Converter::convert($readmeData, $input->getOption('slug'));

        $markdown = $input->getOption('output') ?: $input->getArgument('output');
        if ($markdown) {
            file_put_contents($markdown, $markdownData);
        } else {
            $output->writeln($markdownData, OutputInterface::OUTPUT_RAW);
        }
    }
}
