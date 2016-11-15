<?php

namespace EduardoLedo\ServerSyncBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;

class EduardoledoServerSyncUploadCommand extends ContainerAwareCommand
{

    protected function configure()
    {
        $this
                ->setName('eduardoledo:server-sync:upload')
                ->setDescription('sync files to selected servers')
//            ->addArgument('argument', InputArgument::OPTIONAL, 'Argument description')
                ->addOption('server', null, InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY, 'Server name to sync')
                ->addOption('exclude', null, InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY, 'rsync option')
                ->addOption('exclude-from', null, InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY, 'rsync option')
                ->addOption('dry-run', null, InputOption::VALUE_NONE, 'rsync option')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $rsyncOptions = [];

        if ($input->getOption("dry-run")) {
            $rsyncOptions[] = "--dry-run";
        }

        $exclude = $input->getOption("exclude");
        array_walk($exclude, function($item) use (&$rsyncOptions) {
            $rsyncOptions[] = "--exclude={$item}";
        });

        $excludeFrom = $input->getOption("exclude-from");
        array_walk($excludeFrom, function($item) use (&$rsyncOptions) {
            $rsyncOptions[] = "--exclude-from={$item}";
        });

        // Get servers config
        $servers = $this->getContainer()->getParameter("eduardoledo.server_sync.servers");
        if (count($servers) == 0) {
            $output->writeln([
                "<error>                        </error>",
                '<error> No servers configured. </error>',
                "<error>                        </error>",
            ]);
            exit(1);
        }

        $optServers = $input->getOption('server');
        if (count($optServers) == 0) {
            $output->writeln([
                "<error>                      </error>",
                '<error> No servers selected. </error>',
                "<error>                      </error>",
            ]);
            exit(1);
        }

        $invalidServers = array_diff($optServers, array_keys($servers));
        if (count($invalidServers) > 0) {
            $s = implode(", ", $invalidServers);
            $message = "Invalid server(s): {$s}.";
            $output->writeln([
                "<error> " . str_repeat(" ", strlen($message)) . " </error>",
                "<error> {$message} </error>",
                "<error> " . str_repeat(" ", strlen($message)) . " </error>",
            ]);
            exit(1);
        }

        // Check rsync
        $rsync = null;
        $process = new Process('which rsync');
        $process->run(function ($type, $buffer) use (&$rsync) {
            if (strlen(trim($buffer)) > 0) {
                $rsync = trim($buffer);
            }
        });

        if (is_null($rsync)) {
            $output->writeln([
                "<error>                  </error>",
                '<error> Rsync not found. </error>',
                "<error>                  </error>",
            ]);
            exit(1);
        }
        $output->writeln("Rsync found: <info>{$rsync}</info>", OutputInterface::VERBOSITY_DEBUG);

        foreach ($optServers as $name) {
            $options = "";
            $server = $servers[$name];
            var_dump($server);
            $host = $server['host'];
            if (isset($server['user'])) {
                $user = $server['user'];
                if (isset($server['password'])) {
                    $user = "{$user}:{$server['password']}";
                }
                $host = "{$user}@{$host}";
            }
            if (isset($server["exclude"])) {
                array_walk($server["exclude"], function($item) use (&$rsyncOptions) {
                    $rsyncOptions[] = "--exclude={$item}";
                });
            }
            if (isset($server["exclude-from"])) {
                array_walk($server["exclude-from"], function($item) use (&$rsyncOptions) {
                    $rsyncOptions[] = "--exclude-from={$item}";
                });
            }
            $options = implode(" ", $rsyncOptions);

            $command = "{$rsync} -azvr {$options} {$host}:{$server['destination_dir']}";
            $output->writeln("Uploading to <info>{$name}</info>: <info>{$command}</info>");
            $process = new Process($command);
            $process->run(function ($type, $buffer) use ($output) {
                if ($type == Process::ERR) {
                    $output->writeln(trim("<error>{$buffer}</error>"));
                }
                if ($type == Process::OUT) {
                    $output->writeln(trim("<info>{$buffer}</info>"));
                }
            });
            $process->wait();
        }
    }

}