<?php

namespace SonataVue\Service;

interface BlockInterface
{
	public function buildData(array $options, ?array $routeParams);
}
