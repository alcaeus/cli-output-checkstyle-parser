<?php declare(strict_types = 1);

namespace Alcaeus\CliOutputCheckstyleParser\Data;

final class File implements FileInterface
{
    private $filename;

    private $violations;

    public function __construct(string $filename, array $violations)
    {
        $this->filename = $filename;
        $this->violations = new ViolationList(...$violations);
    }

    public function getName(): string
    {
        return $this->filename;
    }

    public function getViolations(): ViolationListInterface
    {
        return $this->violations;
    }
}
