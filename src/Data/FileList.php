<?php declare(strict_types = 1);

namespace Alcaeus\CliOutputCheckstyleParser\Data;

final class FileList implements FileListInterface
{
    private $files = [];

    public function add(File $file): void
    {
        $this->files[] = $file;
    }

    public function getIterator()
    {
        return new \ArrayIterator($this->files);
    }

    /**
     * @param mixed $offset
     * @return bool
     */
    public function offsetExists($offset)
    {
        return isset($this->files[$offset]);
    }

    /**
     * @param mixed $offset
     * @return File
     */
    public function offsetGet($offset)
    {
        return $this->files[$offset];
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
