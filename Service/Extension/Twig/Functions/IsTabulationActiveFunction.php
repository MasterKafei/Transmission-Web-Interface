<?php

namespace App\Service\Extension\Twig\Functions;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\RouterInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * Class IsTabulationActiveFunction
 * @package App\Service\Extension\Twig\Functions
 * @author Jean Marius <jean.marius@dyosis.com>
 */
class IsTabulationActiveFunction extends AbstractExtension
{
	/**
	 * @var RequestStack
	 */
	private $requestStack;

	/**
	 * @var RouterInterface
	 */
	private $router;

	/**
	 * @var string
	 */
	private $route;

	/**
	 * @var string
	 */
	private $path;

	/**
	 * Is tabulation active function constructor.
	 *
	 * @param RequestStack $requestStack
	 * @param RouterInterface $router
	 */
	public function __construct(RequestStack $requestStack, RouterInterface $router)
	{
		$this->requestStack = $requestStack;
		$this->router = $router;
	}

	public function initAttributes()
	{
		if($this->route !== null && $this->path !== null) {
			return;
		}

		$request = $this->requestStack->getMasterRequest();
		if ($request !== null) {
			$this->route = $request->attributes->get('_route');
			try {
				$this->path = $this->router->generate($this->route, $request->attributes->get('_route_params'));
			} catch (\Exception $exception) {

			}
		}
	}

	/**
	 * Get functions.
	 *
	 * @return TwigFunction[]
	 */
	public function getFunctions()
	{
		return array(
			new TwigFunction('is_tabulation_active', array($this, 'isTabulationActive')),
		);
	}

	/**
	 * Is tabulation active.
	 *
	 * @param $item
	 * @return boolean
	 */
	public function isTabulationActive($item)
	{
		$this->initAttributes();
		$itemRoute = $item['route'] ?? null;
		$itemPath = $item['path'] ?? null;

		if ($itemRoute === $this->route || $itemPath === $this->path) {
			return true;
		}

		$subItems = $item['sub_items'] ?? array();

		foreach ($subItems as $subItem) {
			if ($this->isTabulationActive($subItem)) {
				return true;
			}
		}

		return false;
	}

}
