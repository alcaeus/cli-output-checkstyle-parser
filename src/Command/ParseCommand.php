<?php declare(strict_types = 1);

namespace Alcaeus\CliOutputCheckstyleParser\Command;

use Alcaeus\CliOutputCheckstyleParser\Parser\ParserInterface;
use Alcaeus\CliOutputCheckstyleParser\Parser\PhpStan;
use Alcaeus\CliOutputCheckstyleParser\Parser\Yamllint;
use Alcaeus\CliOutputCheckstyleParser\Writer\CheckstyleWriter;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\StreamableInputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class ParseCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('parse')
            ->addArgument('input-format', InputArgument::REQUIRED)
            ->addArgument('output-file', InputArgument::REQUIRED)
            ->addArgument('input-file', InputArgument::REQUIRED)
            ->setDescription('Parses a given input file and converts it to chekstyle XML.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $format = $input->getArgument('input-format');
        $inputFile = $input->getArgument('input-file');
        $outputFile = $input->getArgument('output-file');

        if (!file_exists($inputFile)) {
            $output->write("Could not load file $inputFile");
            return 1;
        }

        $parser = $this->getParserForFormat($format);
        if (!$parser) {
            $output->write("Could not find parser for format $format");
            return 2;
        }

        $files = $parser->parse(file_get_contents($inputFile));

        $output = (new CheckstyleWriter())->generateOutput($files);
        file_put_contents($outputFile, $output);

        return 0;
    }

    private function getParserForFormat(string $format): ?ParserInterface
    {
        switch ($format) {
            case 'phpstan':
                return new PhpStan();
            case 'yamllint':
                return new Yamllint();
        }

        return null;
    }
}
