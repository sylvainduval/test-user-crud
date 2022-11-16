<?php

namespace App\EventListener;

use Symfony\Component\HttpKernel\Event\ResponseEvent;

class RequestListener
{
	public function onKernelResponse(ResponseEvent $event): void
	{
		if (!$event->isMainRequest()) {
			return;
		}
		$routeName = $event->getRequest()->attributes->get('_route');

		$response = $event->getResponse();

		$response->headers->set('X-ROUTE-APP', $routeName);
	}
}