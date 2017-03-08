<?php declare(strict_types = 1);

namespace Alcaeus\CliOutputCheckstyleParser\Parser;

use Alcaeus\CliOutputCheckstyleParser\Data\File;
use Alcaeus\CliOutputCheckstyleParser\Data\FileList;
use Alcaeus\CliOutputCheckstyleParser\Data\FileListInterface;
use Alcaeus\CliOutputCheckstyleParser\Data\Violation;
use Alcaeus\CliOutputCheckstyleParser\Data\ViolationInterface;

final class PhpStan implements ParserInterface
{
    public function parse(string $output): FileListInterface
    {
        $separator = '\s-+\s-+\s';

        $regexp = <<<REGEXP
#
(?:
    $separator
    \s+Line\s+(?P<filename>\S+)\s*
    $separator
    (?P<errors>.*?)
    $separator
)
#xms
REGEXP;

        preg_match_all($regexp, $output, $matches, PREG_SET_ORDER);

        $fileList = new FileList();

        array_walk($matches, function ($match) use ($fileList) {
            $fileList->add(new File($match['filename'], $this->parseFileErrors($match['errors'])));
        });

        return $fileList;
    }

    private function parseFileErrors($fileErrors): array
    {
        $regexp = <<<REGEXP
#
    ^\s*(?P<line>\d+)\s+(?P<message>.*?)\s*$
#xm
REGEXP;

        preg_match_all($regexp, $fileErrors, $matches, PREG_SET_ORDER);

        return array_map(function ($error) {
            return new Violation((int) $error['line'], 1, $error['message'], ViolationInterface::SEVERITY_ERROR, 'phpstan');
        }, $matches);
    }
}
