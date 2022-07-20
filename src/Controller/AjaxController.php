<?php

declare(strict_types=1);

namespace Page\Controller;

use Doctrine\Persistence\ManagerRegistry;
use Page\Entity\Page;
use Page\Service\AbstractBlockService;
use Page\Type\MapType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AjaxController extends AbstractController
{
	#[Route('/w/ajax/remove-from-slot', name: 'page_ajax_remove_from_slot', methods: ['DELETE'])]
	public function removeFromSlot(Request $request, ManagerRegistry $doctrine): Response
	{
		list($pageId, $slot, $num) = explode('__', $request->get('psn'));
		$num = (int)$num;
		$page = $doctrine->getRepository(Page::class)->find($pageId);
		$page->removeFromSlot($slot, $num);
		$doctrine->getManager()->flush();
		return new Response('');
	}

	#[Route('/w/ajax/change-num', name: 'page_ajax_change_num', methods: ['POST'])]
	public function changeNum(Request $request, ManagerRegistry $doctrine): Response
	{
		list($pageId, $slot, $num) = explode('__', $request->get('psn'));
		$num = (int)$num;
		$page = $doctrine->getRepository(Page::class)->find($pageId);
		/** @var $page Page */
		$page->changeNum($slot, $num, $request->request->getInt('num'));
		$doctrine->getManager()->flush();
		return new Response('');
	}

	#[Route('/w/ajax/form-config', name: 'page_ajax_form_config', methods: ['GET', 'POST'])]
	public function configForm(Request $request, ManagerRegistry $doctrine): Response
	{
		list($form, $page, $service, $slot, $num) = $this->parseSrvString($request->get('pssn'));
		if($request->isMethod(Request::METHOD_POST)){
			$form = $service->getConfigForm($this->createFormBuilder(), $request->get('pssn'), $slot);
			$form->handleRequest($request);
			if($form->isValid()){
				$data = $form->getData();
				/** @var Page $page */
				$page->setSlotConfig($slot, $service::class, $num, $data);
				$doctrine->getManager()->flush();
			}
		}
		return $this->render('@Page/form_placeholder.html.twig',['form'=>$form->createView()]);
	}

	public function renderConfigFormFromSrv(string $pssn): Response
	{
		list($form, , , , ) = $this->parseSrvString($pssn);
		return $this->render('@Page/form_placeholder.html.twig',['form'=>$form->createView()]);
	}

	private function parseSrvString(string $pssn):array{
		list($pageId, $slot, $serviceClassName, $num) = explode('__', $pssn);

		$service = $this->mapType->getBlockServices()[$serviceClassName] ?? null;
		if(is_null($service)){
			throw new \Exception('Unknown service '.$serviceClassName);
		}
		$page = $this->doctrine->getRepository(Page::class)->find($pageId);
		$data = [];
		if($page->getSlotsOptions()[$slot][$num]['service'] ?? ''===$service::class){
			$data = $page->getSlotsOptions()[$slot][$num]['config'];
		}
		/** @var AbstractBlockService $service */
		return [$service->getConfigForm($this->createFormBuilder($data), $pssn, $slot), $page, $service, $slot, (int)$num];
	}

	public function __construct(private readonly ManagerRegistry $doctrine, private readonly MapType $mapType)
	{

	}
}
