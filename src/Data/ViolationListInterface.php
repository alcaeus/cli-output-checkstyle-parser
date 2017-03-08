<?php declare(strict_types = 1);

namespace Alcaeus\CliOutputCheckstyleParser\Data;

interface ViolationListInterface extends \IteratorAggregate, \ArrayAccess
{
    public function add(Violation $violation): void;
}
