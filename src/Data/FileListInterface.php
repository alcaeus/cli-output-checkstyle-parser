<?php declare(strict_types = 1);

namespace Alcaeus\CliOutputCheckstyleParser\Data;

interface FileListInterface extends \IteratorAggregate, \ArrayAccess
{
    public function add(File $file): void;
}
