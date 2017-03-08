<?php declare(strict_types = 1);

namespace Alcaeus\CliOutputCheckstyleParser\Data;

interface ViolationInterface
{
    public const SEVERITY_WARNING = 'warning';
    public const SEVERITY_ERROR = 'error';

    public function getLine(): int;

    public function getColumn(): int;

    public function getMessage(): string;

    public function getSeverity(): string;

    public function getSource(): string;
}
