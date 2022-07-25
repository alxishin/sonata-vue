<?php

declare(strict_types=1);

namespace SonataVue\Subscriber;

use SonataVue\Exception\PageNotFoundException;
use SonataVue\Service\PageService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class ExceptionSubscriber implements EventSubscriberInterface
{
	public static function getSubscribedEvents()
	{
		return [KernelEvents::EXCEPTION => [['processException', 10]]];
	}

	public function __construct(private readonly PageService $pageService)
	{
	}

	/**
	 * Создаем страницу с path вида	#exception_Symfony\Component\HttpKernel\Exception\NotFoundHttpException
	 * И exception данным классом будет отображать как выбранная страница
	 */
	public function processException(ExceptionEvent $event)
	{
		if(!$event->isMainRequest()){
			return;
		}
		//Если страницы для эксепшена нет, он будет выброшен снова
		$event->setResponse($this->pageService->renderPageForException($event->getThrowable()));
	}
}
