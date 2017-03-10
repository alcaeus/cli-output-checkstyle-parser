<?php declare(strict_types = 1);

namespace Alcaeus\CliOutputCheckstyleParser\Parser;

use Alcaeus\CliOutputCheckstyleParser\Data\File;
use Alcaeus\CliOutputCheckstyleParser\Data\FileList;
use Alcaeus\CliOutputCheckstyleParser\Data\FileListInterface;
use Alcaeus\CliOutputCheckstyleParser\Data\Violation;
use Alcaeus\CliOutputCheckstyleParser\Data\ViolationInterface;

final class Yamllint implements ParserInterface
{
    public function parse(string $output): FileListInterface
    {
        $regexp = <<<REGEXP
#
(?P<filename>.*):
(?P<line>\d+):
(?P<column>\d+):\s*
\[(?P<severity>.*)\]\s*
(?P<message>.*?)\s*
\((?P<source>.*)\)
#ix
REGEXP;

        preg_match_all($regexp, $output, $matches, PREG_SET_ORDER);

        $files = [];
        foreach ($matches as $match) {
            $filename = $match['filename'];

            if (!isset($files[$filename])) {
                $files[$filename] = [
                    'filename' => $filename,
                    'violations' => [],
                ];
            }

            $files[$filename]['violations'][] = $this->createViolation($match);
        }

        $fileList = new FileList();
        array_walk($files, function ($file) use ($fileList) {
            $fileList->add(new File($file['filename'], $file['violations']));
        });

        return $fileList;
    }

    private function createViolation(array $match): ViolationInterface
    {
        return new Violation((int) $match['line'], (int) $match['column'], $match['message'], $match['severity'], $match['source']);
    }
}
