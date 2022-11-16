<?php

namespace App\EventSubscriber;

use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class ControllerSubscriber implements EventSubscriberInterface
{
	protected LoggerInterface $httpRequestLogger;

	public function __construct(LoggerInterface $httpRequestLogger)
	{
		$this->httpRequestLogger = $httpRequestLogger;
	}
	public static function getSubscribedEvents(): array
	{
		return [
			KernelEvents::CONTROLLER => [
				'logRequest'
			]
		];
	}

	public function logRequest(ControllerEvent $event): void
	{
		if (!$event->isMainRequest()) {
			return;
		}
		$request = $event->getRequest();

		$ip = $request->getClientIp();
		$routeName = $request->attributes->get('_route');
		if ($routeName === '_wdt') {
			return;
		}

		$this->httpRequestLogger->info('', [
			'ip' => $ip,
			'routeName' => $routeName
		]);
	}
}