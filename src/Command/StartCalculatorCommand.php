<?php

namespace App\Command;

use App\Helper\CalculatorHelper;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'StartCalculator',
    description: 'A basic intro to the calculator',
)]
class StartCalculatorCommand extends Command
{

    private LoggerInterface $logger;
    private EntityManagerInterface $entityManager;

    public function __construct(LoggerInterface $logger,EntityManagerInterface $entityManager)
    {
        $this->logger = $logger;

        parent::__construct();
        $this->entityManager = $entityManager;
    }

    protected function configure(): void
    {
        $this
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $helper = $this->getHelper('question');

        $question = new Question("Please enter the equation: example 1+2*3 \n", '1');
        $equation = $helper->ask($input, $output, $question);

        $this->logger->info("Start Calculations for $equation from CLI");

        $io->info("calculating  $equation");

        $cal = new CalculatorHelper($this->logger,$this->entityManager);
        $cal->startCLILogger();
        $result = json_encode($cal->handleInput($equation));


        $io->success("Result  $result");

        return Command::SUCCESS;
    }
}
