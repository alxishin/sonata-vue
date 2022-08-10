<?php

declare(strict_types=1);

namespace SonataVue\Controller;

use Sonata\AdminBundle\Controller\CRUDController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpKernel\KernelInterface;

final class PageAdminController extends CRUDController{

	public function refreshRoutesAction(KernelInterface $kernel){
		$fs = new Filesystem();
		$dir = $kernel->getCacheDir();
		$filesForRemove = [
			'url_matching_routes.php',
			'url_generating_routes',
			'srcApp_KernelDevDebugContainerUrl',
			'srcApp_KernelProdContainerUrl'
		];
		foreach ($filesForRemove as $file){
			$fs->remove($dir.'/../dev/'.$file);
			$fs->remove($dir.'/../prod/'.$file);
		}

		return $this->redirectToList();
	}
}
