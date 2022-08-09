<?php

declare(strict_types=1);

namespace SonataVue\Controller;

use Doctrine\Persistence\ManagerRegistry;
use SonataVue\Entity\Page;
use SonataVue\Service\AbstractBlockService;
use SonataVue\Type\MapType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AjaxController extends AbstractController
{
	#[Route('/ajax/remove-from-slot', name: 'page_ajax_remove_from_slot', methods: ['DELETE'])]
	public function removeFromSlot(Request $request, ManagerRegistry $doctrine): Response
	{
		$this->checkAccess();
		list($pageId, $slot, $num) = explode('__', $request->get('psn'));
//		$num = (int)$num;
		$page = $doctrine->getRepository(Page::class)->find($pageId);
		$page->removeFromSlot($slot, $num);
		$doctrine->getManager()->flush();
		return new Response('');
	}

	#[Route('/ajax/change-num', name: 'page_ajax_change_num', methods: ['POST'])]
	public function changeNum(Request $request, ManagerRegistry $doctrine): Response
	{
		$this->checkAccess();
		list($pageId, $slot, $num) = explode('__', $request->get('psn'));

		$page = $doctrine->getRepository(Page::class)->find($pageId);
		/** @var $page Page */
		$page->changeNum($slot, $num, $request->request->get('num'));
		$doctrine->getManager()->flush();
		return new Response('');
	}

	#[Route('/ajax/form-config', name: 'page_ajax_form_config', methods: ['GET', 'POST'])]
	public function configForm(Request $request, ManagerRegistry $doctrine): Response
	{
		$this->checkAccess();
		list($form, $page, $service, $slot, $num) = $this->parsePssnString($request->get('pssn'));
		if($request->isMethod(Request::METHOD_POST)){
			$form = $service->getConfigForm($this->getFormConfigBuilder(null, $slot, $num), $request->get('pssn'), $slot, $num);
			$form->handleRequest($request);
			if($form->isValid()){
				$data = $form->getData();
				/** @var Page $page */
				$page->setSlotConfig($slot, $service::class, $num, $data);
				$doctrine->getManager()->flush();
			}
		}
		return $this->render('@SonataVue/form_placeholder.html.twig',['form'=>$form->createView()]);
	}

	public function renderConfigFormFromPssn(string $pssn): Response
	{
		$this->checkAccess();
		list($form, , , , ) = $this->parsePssnString($pssn);
		return $this->render('@SonataVue/form_placeholder.html.twig',['form'=>$form->createView()]);
	}

	private function parsePssnString(string $pssn):array{
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
		return [$service->getConfigForm($this->getFormConfigBuilder($data, $slot, $num), $pssn, $slot, $num), $page, $service, $slot, $num];
	}

	private function checkAccess(){
		$this->denyAccessUnlessGranted($this->getParameter('sonata_vue.role_for_ajax_request'));
	}

	private function getFormConfigBuilder($data, string $slot, string $num):FormBuilderInterface{
		return $this->formFactory->createNamedBuilder($slot.'_'.$num, FormType::class, $data);
	}

	public function __construct(private readonly ManagerRegistry $doctrine, private readonly MapType $mapType, private FormFactoryInterface $formFactory)
	{

	}
}
