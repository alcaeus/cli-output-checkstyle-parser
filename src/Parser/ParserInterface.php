<?php declare(strict_types = 1);

namespace Alcaeus\CliOutputCheckstyleParser\Parser;

use Alcaeus\CliOutputCheckstyleParser\Data\FileListInterface;

interface ParserInterface
{
    public function parse(string $output): FileListInterface;
}
