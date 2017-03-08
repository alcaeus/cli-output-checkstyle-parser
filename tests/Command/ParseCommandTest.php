<?php declare(strict_types = 1);

namespace Tests\Alcaeus\CliOutputCheckstyleParser\Parser;

use Alcaeus\CliOutputCheckstyleParser\Command\ParseCommand;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Tester\CommandTester;

final class ParseCommandTest extends TestCase
{
    public function testRunCommand()
    {
        $outputFile = tempnam(sys_get_temp_dir(), 'cli-output-checkstyle-parser');

        try {
            $commandTester = new CommandTester(new ParseCommand());
            $commandTester->execute([
                'input-format' => 'phpstan',
                'input-file' => __DIR__ . '/../files/phpstan-cli-output.txt',
                'output-file' => $outputFile,
            ]);

            self::assertXmlFileEqualsXmlFile(__DIR__ . '/../files/phpstan-checkstyle-output.xml', $outputFile);
            self::assertSame(0, $commandTester->getStatusCode());
        } finally {
            @unlink($outputFile);
        }
    }

    public function testRunWithInvalidInputFile()
    {
        $outputFile = __DIR__ . '/../files/should-not-exist.xml';
        $commandTester = new CommandTester(new ParseCommand());
        $commandTester->execute([
            'input-format' => 'phpstan',
            'input-file' => __DIR__ . '/../files/does-not-exist.txt',
            'output-file' => $outputFile,
        ]);

        self::assertSame(1, $commandTester->getStatusCode());
        self::assertFileNotExists($outputFile);
    }

    public function testRunWithInvalidParser()
    {
        $outputFile = __DIR__ . '/../files/should-not-exist.xml';
        $commandTester = new CommandTester(new ParseCommand());
        $commandTester->execute([
            'input-format' => 'no-you-will-not-create-this-format',
            'input-file' => __DIR__ . '/../files/phpstan-cli-output.txt',
            'output-file' => $outputFile,
        ]);

        self::assertSame(2, $commandTester->getStatusCode());
        self::assertFileNotExists($outputFile);
    }
}
