<?php
namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Install extends Command
{

    protected function configure()
    {
        $this->setName("app:install")
            ->setDescription('Install application');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $commands = $this->getCommandsToRun();
        if ($output->getVerbosity()) {
            $commands['update'] .= ' --dump-sql';
        }
        foreach ($commands as $command) {
            passthru($command);
        }
    }

    /**
     * @return array
     */
    protected function getCommandsToRun()
    {
        return [
            'update' => 'bin/console doctrine:schema:update --force',
            'migrate' => 'bin/console doctrine:migration:migrate',
            'user' => 'bin/console user:create'
        ];
    }

}
