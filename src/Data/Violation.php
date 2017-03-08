<?php declare(strict_types = 1);

namespace Alcaeus\CliOutputCheckstyleParser\Data;

final class Violation implements ViolationInterface
{
    private $line;

    private $column;

    private $message;

    private $severity;

    private $source;

    public function __construct(int $line, int $column, string $message, string $severity, string $source)
    {
        $this->line = $line;
        $this->column = $column;
        $this->message = $message;
        $this->severity = $severity;
        $this->source = $source;
    }

    public function getLine(): int
    {
        return $this->line;
    }

    public function getColumn(): int
    {
        return $this->column;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function getSeverity(): string
    {
        return $this->severity;
    }

    public function getSource(): string
    {
        return $this->source;
    }
}
