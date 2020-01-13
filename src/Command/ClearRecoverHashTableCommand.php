<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use App\Service\UserService;

class ClearRecoverHashTableCommand extends Command
{
    protected static $defaultName = 'app:clear-recover-hash-table';

    public function __construct(UserService $userService)
    {
        // best practices recommend to call the parent constructor first and
        // then set your own properties. That wouldn't work in this case
        // because configure() needs the properties set in this constructor
        $this->userService = $userService;

        parent::__construct();
    }

    protected function configure() {
       
    }

    protected function execute(InputInterface $input, OutputInterface $output) {
        $this
            ->setDescription('Clears the RecoverHash table');

        try{
            $this->userService->removeExpiredRecoverHash();
            $output->writeln('Acción realizada correctamente');
        } catch(\Exception $e) {
            $output->writeln('Error: '.$e->getMessage());
        }
        
    }
}
