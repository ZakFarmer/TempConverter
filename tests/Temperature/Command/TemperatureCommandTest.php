<?php
namespace Tests\Temperature\Command;

use PHPUnit\Framework\TestCase;

use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\Console\Application;

use Temperature\Command\ConvertTemperatureCommand;

final class TemperatureCommandTest extends TestCase 
{
    private $commandTester;

    protected function setUp(): void
    {
        $app = new Application();
        $app->add(new ConvertTemperatureCommand());

        $command = $app->find('convert');
        $this->commandTester = new CommandTester($command);

    }

    public function testConvertCelsiusToFahrenheitPositive(): void
    {
        $this->commandTester->execute([
            'temperature' => '100', 
            'unit' => 'c'
        ]);

        $output = preg_replace("/\r|\n/", "", $this->commandTester->getDisplay()); // Trim line breaks and carriage returns so raw text can be compared.

        $this->assertEquals('The temperature is 212 degrees Fahrenheit.', $output);
    }

    public function testConvertFahrenheitToCelciusPositive(): void 
    {
        $this->commandTester->execute([
            'temperature' => '212',
            'unit' => 'f'
        ]);

        $output = preg_replace("/\r|\n/", "", $this->commandTester->getDisplay());

        $this->assertEquals('The temperature is 100 degrees Celsius.', $output);
    }

    public function testConvertCelsiusToFahrenheitNegative(): void
    {
        $this->commandTester->execute([
            'temperature' => '-100',
            'unit' => 'c'
        ]);

        $output = preg_replace("/\r|\n/", "", $this->commandTester->getDisplay());

        $this->assertEquals('The temperature is -148 degrees Fahrenheit.', $output);
    }

    public function testConvertFahrenheitToCelciusNegative(): void
    {
        $this->commandTester->execute([
            'temperature' => '-212',
            'unit' => 'f'
        ]);

        $output = preg_replace("/\r|\n/", "", $this->commandTester->getDisplay());

        $this->assertEquals('The temperature is -135.56 degrees Celsius.', $output);
    }

    public function testConvertInvalidUnit(): void 
    {
        $this->commandTester->execute([
            'temperature' => '100',
            'unit' => 'x'
        ]);

        $output = preg_replace("/\r|\n/", "", $this->commandTester->getDisplay());

        $this->assertEquals('Please specify a valid unit.', $output);
    }

    public function testConvertInvalidTemperature(): void {
        $this->commandTester->execute([
            'temperature' => 'x',
            'unit' => 'c'
        ]);

        $output = preg_replace("/\r|\n/", "", $this->commandTester->getDisplay());

        $this->assertEquals('Please specify a valid temperature.', $output);
    }
}