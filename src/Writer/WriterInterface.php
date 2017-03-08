<?php declare(strict_types = 1);

namespace Alcaeus\CliOutputCheckstyleParser\Writer;

use Alcaeus\CliOutputCheckstyleParser\Data\FileListInterface;

interface WriterInterface
{
    public function generateOutput(FileListInterface $files): string;
}
