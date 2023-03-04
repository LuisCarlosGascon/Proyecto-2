<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Mesa;
use Doctrine\ORM\EntityManager;

#[AsCommand(
    name: 'prueba',aliases:['prueba2']
)]
class PruebaCommand extends Command
{
    private $em;
    protected static $defaultName = 'app:author-weekly-report:send';

    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct();
        $this->em=$em;
    }

    protected function configure()
    {
        $this
            ->setDescription('Add a short description for your command')
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        
        $mesa= new Mesa();

        $alto=$io->ask('Alto de la Mesa');
        $mesa->setAlto(intval($alto));

        $ancho=$io->ask('Ancho de la Mesa');
        $mesa->setAncho(intval($ancho));

        $sillas=$io->ask('Sillas de la Mesa');
        $mesa->setSillas(intval($sillas));

        $mesa->setX(null);
        $mesa->setY(null);

        
        $this->em->persist($mesa);
        $this->em->flush();
 
        return 0;
    }
}
