<?php
namespace StartCompany\Component\StartComponent\Site\Service;

defined('JPATH_PLATFORM') or die;

use Joomla\CMS\Component\Router\RouterView;
use Joomla\CMS\Component\Router\Rules\RulesInterface;

class StartComponentNomenuRules implements RulesInterface
{
	private $_noMenuViews = ['&views&'];
	protected $router;

	public function __construct(RouterView $router)
	{
		$this->router = $router;
	}

	public function preprocess(&$query)
	{
		$test = 'Test';
	}

	public function parse(&$segments, &$vars)
	{
		$vars['view'] = explode('-', $segments[0])[0];
		$vars['id'] = substr($segments[0], strpos($segments[0], '-') + 1);
		array_shift($segments);
		array_shift($segments);
		return;
	}

	public function build(&$query, &$segments)
	{
		if (!isset($query['view']) ||
			(isset($query['view']) && ( !in_array($query['view'], $this->_noMenuViews )) )
			|| isset($query['format']))
		{
			return;
		}

		$segments[] = $query['view'] . '-' . $query['id'];
		// the last part of the url may be missing
		if (isset($query['slug'])) {
			$segments[] = $query['slug'];
			unset($query['slug']);
		}
		unset($query['view']);
		unset($query['id']);
	}
}

