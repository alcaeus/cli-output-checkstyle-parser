<?php declare(strict_types = 1);

namespace Alcaeus\CliOutputCheckstyleParser\Data;

final class ViolationList implements ViolationListInterface
{
    private $violations = [];

    public function __construct(Violation ...$violations)
    {
        $this->violations = $violations;
    }

    public function add(Violation $violation): void
    {
        $this->violations[] = $violation;
    }

    /**
     * @return \ArrayIterator
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->violations);
    }

    /**
     * @param mixed $offset
     * @return bool
     */
    public function offsetExists($offset)
    {
        return isset($this->violations[$offset]);
    }

    /**
     * @param mixed $offset
     * @return Violation
     */
    public function offsetGet($offset)
    {
        return $this->violations[$offset];
    }

    public function offsetSet($offset, $value)
    {
        throw new \RuntimeException('Not implemented');
    }

    public function offsetUnset($offset)
    {
        throw new \RuntimeException('Not implemented');
    }
}
