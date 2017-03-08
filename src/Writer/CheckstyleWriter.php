<?php declare(strict_types = 1);

namespace Alcaeus\CliOutputCheckstyleParser\Writer;

use Alcaeus\CliOutputCheckstyleParser\Data\File;
use Alcaeus\CliOutputCheckstyleParser\Data\FileListInterface;
use Alcaeus\CliOutputCheckstyleParser\Data\Violation;

final class CheckstyleWriter implements WriterInterface
{
    private const XML_HEADER = '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL;

    public function generateOutput(FileListInterface $files): string
    {
        $writer = new \XMLWriter();
        $writer->openMemory();
        $writer->setIndent(true);

        $writer->startElement('checkstyle');

        array_map(function (File $file) use ($writer) {
            $this->writeFile($writer, $file);
        }, iterator_to_array($files));

        $writer->endElement();

        return static::XML_HEADER . $writer->flush();
    }

    private function writeFile(\XMLWriter $writer, File $file): void
    {
        $writer->startElement('file');
        $writer->writeAttribute('name', $file->getName());

        array_map(function (Violation $violation) use ($writer) {
            $this->writeViolation($writer, $violation);
        }, iterator_to_array($file->getViolations()));

        $writer->endElement();
    }

    private function writeViolation(\XMLWriter $writer, Violation $violation): void
    {
        $writer->startElement('error');
        $writer->writeAttribute('line', (string) $violation->getLine());
        $writer->writeAttribute('column', (string) $violation->getColumn());
        $writer->writeAttribute('severity', $violation->getSeverity());
        $writer->writeAttribute('message', $violation->getMessage());
        $writer->writeAttribute('source', $violation->getSource());
        $writer->endElement();
    }
}
