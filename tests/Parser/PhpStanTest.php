<?php declare(strict_types = 1);

namespace Tests\Alcaeus\CliOutputCheckstyleParser\Parser;

use Alcaeus\CliOutputCheckstyleParser\Data\Violation;
use Alcaeus\CliOutputCheckstyleParser\Parser\PhpStan;
use PHPUnit\Framework\TestCase;

final class PhpStanTest extends TestCase
{
    public function testParse()
    {
        $output = file_get_contents(__DIR__ . '/../files/phpstan-cli-output.txt');
        $fileList = (new PhpStan())->parse($output);

        self::assertCount(2, $fileList);

        self::assertSame('src/Sample/FirstFile.php', $fileList[0]->getName());
        self::assertSame('src/Sample/SecondFile.php', $fileList[1]->getName());

        self::assertCount(1, $fileList[0]->getViolations());
        self::assertCount(5, $fileList[1]->getViolations());

        $expectedViolation = new Violation(91, 1, 'Strict comparison using === between false and true will always evaluate to false.', 'error', 'phpstan');

        self::assertEquals($expectedViolation, $fileList[0]->getViolations()[0]);
    }

    public function testParseMultilineError()
    {
        $output = file_get_contents(__DIR__ . '/../files/phpstan-cli-output-multiline.txt');

        $fileList = (new PhpStan())->parse($output);

        self::assertCount(1, $fileList);

        self::assertSame('src/AppBundle/Document/User.php', $fileList[0]->getName());

        self::assertCount(1, $fileList[0]->getViolations());

        $expectedViolation = new Violation(1581, 1, 'Return typehint of method AppBundle\Document\User::getEmailAliasMappings() has invalid type AppBundle\Document\EmailAliasMapping.', 'error', 'phpstan');

        self::assertEquals($expectedViolation, $fileList[0]->getViolations()[0]);
    }

    public function testParseFileLevelError()
    {
        $output = file_get_contents(__DIR__ . '/../files/phpstan-cli-output-file-level.txt');

        $fileList = (new PhpStan())->parse($output);

        self::assertCount(1, $fileList);

        self::assertSame('tests/AppBundle/Document/User/LegalInformation/PaymentTest.php', $fileList[0]->getName());

        self::assertCount(1, $fileList[0]->getViolations());

        $expectedViolation = new Violation(1, 1, 'Class Tests\AppBundle\Document\User\PaymentTest was not found while trying to analyse it - autoloading is not probably configured properly.', 'error', 'phpstan');

        self::assertEquals($expectedViolation, $fileList[0]->getViolations()[0]);
    }
}
