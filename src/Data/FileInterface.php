<?php declare(strict_types = 1);

namespace Alcaeus\CliOutputCheckstyleParser\Data;

interface FileInterface
{
    public function getName(): string;

    public function getViolations(): ViolationListInterface;
}
