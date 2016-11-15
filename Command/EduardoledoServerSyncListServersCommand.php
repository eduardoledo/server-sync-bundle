<?php

namespace EduardoLedo\ServerSyncBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Console\Helper\Table;

class EduardoledoServerSyncListServersCommand extends ContainerAwareCommand
{

    protected function configure()
    {
        $this
                ->setName('eduardoledo:server-sync:list-servers')
                ->setDescription('...')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $servers = $this->getContainer()->getParameter("eduardoledo.server_sync.servers");

        if (count($servers) > 0) {
            $output->writeln('');
            $table = new Table($output);
            $table->setHeaders(['Name', 'Host', 'User', 'Pass', 'Dest. dir']);
            $rows = [];
            foreach ($servers as $name => $data) {
                $rows[] = [
                    $name,
                    $data['host'],
                    (isset($data['user']) && strlen(trim($data['user'])) > 0) ? $data['user'] : '',
                    (isset($data['password']) && strlen(trim($data['password'])) > 0) ? $data['password'] : '',
                    $data['destination_dir']
                ];
            }
            $table->setRows($rows);
            $table->render();
        } else {
            $output->writeln('No servers configured.');
        }
        $output->writeln('');
    }

}
