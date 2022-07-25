<?php

declare(strict_types=1);

namespace SonataVue\Routing;

use Doctrine\Persistence\ManagerRegistry;
use Page\Controller\Page\MainController;
use SonataVue\Controller\IndexAction;
use SonataVue\Entity\Page;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

class RouterLoader extends \Symfony\Component\Config\Loader\Loader
{
	private $isLoaded = false;

	public function __construct(private string $routePrefix, private readonly ManagerRegistry $doctrine)
	{
	}


	public function load($resource, string $type = null)
	{
		if (true === $this->isLoaded) {
			throw new \RuntimeException('Do not add the "extra" loader twice');
		}

		$routes = new RouteCollection();

		/** @var Page $page */
		foreach ($this
					 ->doctrine
					 ->getRepository(Page::class)
					 ->createQueryBuilder('p')
					 ->join('p.site','s')
					 ->where('s.published=true and p.published=true')
					 ->getQuery()
					 ->getResult() as $page){

			if(!$page->getSite()->getHostNames()){
				$this->addRouteFromPage($routes, $page);
				continue;
			}
			foreach ($page->getSite()->getHostNames() as $hostName){
				$this->addRouteFromPage($routes, $page, $hostName);
			}
		}

		$this->isLoaded = true;

		return $routes;
	}

	private function addRouteFromPage(RouteCollection $routes, Page $page, string $hostName = ''){

		$routes->add('sonata_vue_page_'.$page->getId(), new Route(
			$this->routePrefix.$page->getPath(),
			array_merge(['_page_id' =>$page->getId(),'_controller'=>IndexAction::class], $page->getPreparedDefaults()),
			$page->getPreparedRequirements(),
			[],
			$hostName));
	}


	public function supports($resource, string $type = null)
	{
		return 'extra' === $type;
	}
}
