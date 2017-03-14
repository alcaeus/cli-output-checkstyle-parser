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
        $matches = preg_split('#^\s*(?P<line>\d+)\s+#m', $fileErrors, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);

        $violations = [];
        while ($match = array_shift($matches)) {
            if (preg_match('#^\d+#', $match)) {
                $line = (int) $match;
                $message = array_shift($matches);
            } else {
                $line = 1;
                $message = $match;
            }

            $message = preg_replace('#\s+#', ' ', trim($message));
            $violations[] = new Violation($line, 1, $message, ViolationInterface::SEVERITY_ERROR, 'phpstan');
        }

        return $violations;
    }
}
