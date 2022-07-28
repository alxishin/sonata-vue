<?php

declare(strict_types=1);

namespace SonataVue\Service;

use Doctrine\Persistence\ManagerRegistry;
use SonataVue\Entity\Page;
use SonataVue\Type\MapType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;

class PageService
{
	public function __construct(private readonly MapType $mapType, private readonly ManagerRegistry $doctrine, private readonly RequestStack $requestStack)
	{
	}

	public function renderPage(Page $page){
		$result = [];
		foreach ($page->getSlotsOptions() ?? [] as $slot=>$configs){
			foreach ($configs as $num=>$config){
				$result[$slot][$num] = $this->mapType->getBlockServices()[$config['service']]->buildData($config['config'], $this->requestStack->getMainRequest()->attributes->get('_route_params'));
//				$result[$slot][] = $this->mapType->getBlockServices()[$config['service']]->buildData($config['config'], $this->requestStack->getMainRequest()->attributes->get('_route_params'));
			}
		}

		switch ($page->getResponseType()){
			case Page::RESPONSE_TYPE_JSON && is_array($result):
				return new JsonResponse($result);
//			case Page::RESPONSE_TYPE_HTML && is_string($result):
//				return new Response($result);
//			case Page::RESPONSE_TYPE_TEXT && is_string($result):
//				return new Response($result, 200, ['Content-type'=>'text/plain']);
			default:
				throw new \Exception('Wrong response type');
		}
	}

	public function renderPageForException(\Throwable $exception){
		$page = $this->doctrine->getRepository(Page::class)->findOneByPath('#exception_'.$exception::class);
		if(!$page){
			throw $exception;
		}

		return $this->renderPage($page);
	}
}
