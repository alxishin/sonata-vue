<?php

namespace SonataVue\Service;

interface BlockServiceInterface
{
	public function buildData(array $options, ?array $routeParams);
}
