<?php declare(strict_types = 1);

namespace Tests\Alcaeus\CliOutputCheckstyleParser\Writer;

use Alcaeus\CliOutputCheckstyleParser\Data\File;
use Alcaeus\CliOutputCheckstyleParser\Data\FileList;
use Alcaeus\CliOutputCheckstyleParser\Data\Violation;
use Alcaeus\CliOutputCheckstyleParser\Data\ViolationInterface;
use Alcaeus\CliOutputCheckstyleParser\Writer\CheckstyleWriter;
use PHPUnit\Framework\TestCase;

final class CheckstyleWriterTest extends TestCase
{
    public function testParse()
    {
        $output = (new CheckstyleWriter())->generateOutput($this->getFileList());

        self::assertXmlStringEqualsXmlFile(__DIR__ . '/../files/phpstan-checkstyle-output.xml', $output);
    }

    private function getFileList()
    {
        $violations = [new Violation(91, 1, 'Strict comparison using === between false and true will always evaluate to false.', ViolationInterface::SEVERITY_ERROR, 'phpstan')];
        $firstFile = new File('src/Sample/FirstFile.php', $violations);

        $violations = [
            new Violation(38, 1, 'Access to an undefined property LibXMLError::$level.', ViolationInterface::SEVERITY_ERROR, 'phpstan'),
            new Violation(41, 1, 'Access to an undefined property LibXMLError::$code.', ViolationInterface::SEVERITY_ERROR, 'phpstan'),
            new Violation(41, 1, 'Access to an undefined property LibXMLError::$message.', ViolationInterface::SEVERITY_ERROR, 'phpstan'),
            new Violation(47, 1, 'Access to an undefined property LibXMLError::$message.', ViolationInterface::SEVERITY_ERROR, 'phpstan'),
            new Violation(47, 1, 'Access to an undefined property LibXMLError::$code.', ViolationInterface::SEVERITY_ERROR, 'phpstan'),
        ];
        $secondFile = new File('src/Sample/SecondFile.php', $violations);

        $fileList = new FileList();
        $fileList->add($firstFile);
        $fileList->add($secondFile);

        return $fileList;
    }
}
