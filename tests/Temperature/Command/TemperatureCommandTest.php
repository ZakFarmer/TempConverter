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

    // Custom assertion to save reusing the same code to trim linebreaks/returns for each test.
    public function assertEqualsTrimmed($expected, $actual)
    {
        $this->assertEquals(trim($expected), trim($actual));
    }

    public function testConvertCelsiusToFahrenheitPositive(): void
    {
        $this->commandTester->execute([
            'temperature' => '100', 
            'unit' => 'c'
        ]);

        $this->assertEqualsTrimmed('The temperature is 212 degrees Fahrenheit.', $this->commandTester->getDisplay());
    }

    public function testConvertFahrenheitToCelsiusPositive(): void 
    {
        $this->commandTester->execute([
            'temperature' => '212',
            'unit' => 'f'
        ]);

        $this->assertEqualsTrimmed('The temperature is 100 degrees Celsius.', $this->commandTester->getDisplay());
    }

    public function testConvertCelsiusToFahrenheitNegative(): void
    {
        $this->commandTester->execute([
            'temperature' => '-100',
            'unit' => 'c'
        ]);

        $output = preg_replace("/\r|\n/", "", $this->commandTester->getDisplay());

        $this->assertEqualsTrimmed('The temperature is -148 degrees Fahrenheit.', $this->commandTester->getDisplay());
    }

    public function testConvertFahrenheitToCelsiusNegative(): void
    {
        $this->commandTester->execute([
            'temperature' => '-212',
            'unit' => 'f'
        ]);

        $output = preg_replace("/\r|\n/", "", $this->commandTester->getDisplay());

        $this->assertEqualsTrimmed('The temperature is -135.56 degrees Celsius.', $this->commandTester->getDisplay());
    }

    public function testConvertInvalidUnit(): void 
    {
        $this->commandTester->execute([
            'temperature' => '100',
            'unit' => 'x'
        ]);

        $output = preg_replace("/\r|\n/", "", $this->commandTester->getDisplay());

        $this->assertEqualsTrimmed('Please specify a valid unit.', $this->commandTester->getDisplay());
    }

    public function testConvertInvalidTemperature(): void {
        $this->commandTester->execute([
            'temperature' => 'x',
            'unit' => 'c'
        ]);

        $output = preg_replace("/\r|\n/", "", $this->commandTester->getDisplay());

        $this->assertEqualsTrimmed('Please specify a valid temperature.', $this->commandTester->getDisplay());
    }
}