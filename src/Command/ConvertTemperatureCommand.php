<?php
namespace Temperature\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ConvertTemperatureCommand extends Command 
{ 
    protected static $defaultName = 'convert';

    protected function configure() 
    {
        $this->addArgument('temperature', InputArgument::REQUIRED, 'The temperature to convert.');
        $this->addArgument('unit', InputArgument::REQUIRED, 'The unit of the temperature.');

        $this->setDescription('Convert a temperature from Celsius to Fahrenheit and vice versa.');
        $this->setHelp('This command allows you to convert a temperature from Celsius to Fahrenheit and vice versa.');
    }

    protected function execute (InputInterface $input, OutputInterface $output) 
    {       
        $temperature = $input->getArgument('temperature');

        if (is_numeric($temperature)) {
            // Check that the temperature is a numeric value before converting. 
            $unit = strtolower($input->getArgument('unit')); // Make unit string lowercase for easier comparison.

            if ($unit == 'c' || $unit == 'celsius') {
                $temperature = $input->getArgument('temperature');
                $temperature = $temperature * 9 / 5 + 32;

                $temperature = round($temperature, 2); // Round temperature to 2 d.p.
                $output->writeln('The temperature is ' . $temperature . ' degrees Fahrenheit.');
            } elseif ($unit == 'f' || $unit == 'fahrenheit') {
                $temperature = $input->getArgument('temperature');
                $temperature = ($temperature - 32) * 5 / 9;

                $temperature = round($temperature, 2); // Round temperature to 2 d.p.
                $output->writeln('The temperature is ' . $temperature . ' degrees Celsius.');
            } else {
                $output->writeln('Please specify a valid unit.');
                return self::FAILURE;
            }
        } else {
            $output->writeln('Please specify a valid temperature.');
            return self::FAILURE;
        }
    
        return self::SUCCESS;
    }
}