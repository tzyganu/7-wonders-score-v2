<?php
namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Update extends Install
{

    protected function configure()
    {
        $this->setName("app:update")
            ->setDescription('Update application');
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
        $commands = parent::getCommandsToRun();
        unset($commands['user']);
        return $commands;
    }

}
