<?php

namespace App\Logger\Handler;

//use App\Entity\Log;
use App\Entity\Log;
use App\Repository\LogRepository;
use Doctrine\Persistence\ManagerRegistry;
use Monolog\Handler\AbstractProcessingHandler;

class RequestHandler extends AbstractProcessingHandler
{
	private LogRepository $logRepository;

	public function __construct(ManagerRegistry $doctrine)
	{
		$this->logRepository = $doctrine->getRepository(Log::class);
		parent::__construct();
	}

	protected function write(array $record): void
	{
		$log = new Log();
		$log->setIp($record['context']['ip']);
		$log->setRoute($record['context']['routeName']);
		$log->setDate($record['datetime']);

		$this->logRepository->save($log, true);
	}
}