<?php

declare(strict_types=1);

namespace SonataVue\Controller;

use Doctrine\Persistence\ManagerRegistry;
use Page\Entity\Page;
use Page\Service\BlockInterface;
use Page\Type\MapType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class IndexAction extends AbstractController
{
	#[Route('/{vueRouting}', name: 'page_main', requirements: ['vueRouting' => '^(?!ajax).*'])]
	public function __invoke(MapType $mapType, ManagerRegistry $doctrine, string $vueRouting = ''): Response
	{
		$page = $doctrine->getRepository(Page::class)->findOneBy(['path'=>'/'.$vueRouting]);
		$result = [];
		foreach ($page->getSlotsOptions() as $slot=>$configs){
			foreach ($configs as $num=>$config){
				$result[$slot][$num] = $mapType->getBlockServices()[$config['service']]->buildData($config['config']);
			}
		}
		return $this->json($result);
	}
}
