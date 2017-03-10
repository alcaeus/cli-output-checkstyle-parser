<?php declare(strict_types = 1);

namespace Tests\Alcaeus\CliOutputCheckstyleParser\Parser;

use Alcaeus\CliOutputCheckstyleParser\Data\Violation;
use Alcaeus\CliOutputCheckstyleParser\Parser\Yamllint;
use PHPUnit\Framework\TestCase;

final class YamllintTest extends TestCase
{
    public function testParse()
    {
        $output = file_get_contents(__DIR__ . '/../files/yamllint-cli-output.txt');
        $fileList = (new Yamllint())->parse($output);

        self::assertCount(2, $fileList);

        self::assertSame('app/config/config.yml', $fileList[0]->getName());
        self::assertSame('app/config/parameters.yml', $fileList[1]->getName());

        self::assertCount(2, $fileList[0]->getViolations());
        self::assertCount(1, $fileList[1]->getViolations());

        $expectedViolation = new Violation(4, 1, 'missing document start "---"', 'warning', 'document-start');

        self::assertEquals($expectedViolation, $fileList[0]->getViolations()[0]);
    }
}
