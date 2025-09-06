<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(name: 'update', description: 'Update the database')]
class UpdateCommand extends Command
{
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $scriptPath = __DIR__ . '/updateMac.sh';

        if (file_exists($scriptPath)) {
            $io->note('Executing update script...');

            // Exécuter le script shell
            $outputShell = shell_exec($scriptPath);

            $io->success("Update script executed successfully:");
            $io->text($outputShell);

            return Command::SUCCESS;
        } else {
            $io->error("Update script not found at $scriptPath");

            return Command::FAILURE;
        }
    }
}