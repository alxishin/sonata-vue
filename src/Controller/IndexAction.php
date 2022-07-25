<?php

declare(strict_types=1);

namespace SonataVue\Controller;

use Doctrine\Persistence\ManagerRegistry;
use SonataVue\Entity\Page;
use SonataVue\Exception\PageNotFoundException;
use SonataVue\Service\BlockInterface;
use SonataVue\Service\PageService;
use SonataVue\Type\MapType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class IndexAction extends AbstractController
{
	public function __invoke(Request $request, PageService $pageService, ManagerRegistry $doctrine): Response
	{
		return $pageService->renderPage($doctrine->getRepository(Page::class)->find($request->attributes->getInt('_page_id')));
	}
}
