<?php

declare(strict_types=1);

namespace PiWeb\PiCRUD\Command;

use Doctrine\ORM\EntityManager;
use PiWeb\PiCRUD\Repository\LogRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class ClearLogCommand
 * @package PiWeb\PiCRUD\Command
 */
class ClearLogCommand extends Command
{
    /**
     * @var string|null The default command name
     */
    protected static $defaultName = 'pi-crud:log:clear';

    /**
     * UpdateFederalArchiveCommand constructor.
     * @param null $name
     */
    public function __construct(
        private LogRepository $logRepository,
        $name = null
    ) {
        parent::__construct($name);
    }

    /**
     * @inheritDoc
     */
    protected function configure()
    {
        $this
            ->setDescription('Suppression des logs de plus de 7 jours')
            ->setHelp('Cette commande vous permet de supprimer les logs de plus de 7 jours.')
        ;
    }

    /**
     * @inheritDoc
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('Nettoyage des logs en cours');

        $this->logRepository->clean();

        $output->writeln('Nettoyage des logs terminé');

        return Command::SUCCESS;
    }
}
