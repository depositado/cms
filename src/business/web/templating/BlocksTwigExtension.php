<?php
namespace Blocks;

/**
 *
 */
class BlocksTwigExtension extends \Twig_Extension
{
	/**
	 * Returns the token parser instances to add to the existing list.
	 *
	 * @return array An array of Twig_TokenParserInterface or Twig_TokenParserBrokerInterface instances
	 */
	public function getTokenParsers()
	{
		return array(
			new Redirect_TokenParser(),
			new IncludeCss_TokenParser(),
			new IncludeJs_TokenParser(),
			new IncludeTranslation_TokenParser(),
		);
	}

	/**
	 * Returns a list of filters to add to the existing list.
	 *
	 * @return array An array of filters
	 */
	public function getFilters()
	{
		$translateFilter = new \Twig_Filter_Function('\Blocks\Blocks::t');
		return array(
			't'          => $translateFilter,
			'translate'  => $translateFilter,
			'decimal'    => new \Twig_Filter_Function('\Blocks\blx()->numberFormatter->formatDecimal'),
			'currency'   => new \Twig_Filter_Function('\Blocks\blx()->numberFormatter->formatCurrency'),
			'percentage' => new \Twig_Filter_Function('\Blocks\blx()->numberFormatter->formatPercentage'),
			'datetime'   => new \Twig_Filter_Function('\Blocks\blx()->dateFormatter->formatDateTime'),
			'without'    => new \Twig_Filter_Method($this, 'withoutFilter'),
		);
	}

	/**
	 * Returns an array without certain values.
	 *
	 * @param array $arr
	 * @param mixed $exclude
	 * @return array
	 */
	public function withoutFilter($arr, $exclude)
	{
		$filteredArray = array();

		if (!is_array($exclude))
			$exclude = array($exclude);

		foreach ($arr as $key => $value)
		{
			if (!in_array($value, $exclude))
				$filteredArray[$key] = $value;
		}

		return $filteredArray;
	}

	/**
	 * Returns a list of functions to add to the existing list.
	 *
	 * @return array An array of functions
	 */
	public function getFunctions()
	{
		return array(
			'url'         => new \Twig_Function_Function('\Blocks\UrlHelper::generateUrl'),
			'resourceUrl' => new \Twig_Function_Function('\Blocks\UrlHelper::generateResourceUrl'),
			'actionUrl'   => new \Twig_Function_Function('\Blocks\UrlHelper::generateActionUrl'),
			'round'       => new \Twig_Function_Function('round'),
			'ceil'        => new \Twig_Function_Function('ceil'),
			'floor'       => new \Twig_Function_Function('floor'),
		);
	}

	/**
	 * Returns a list of global variables to add to the existing list.
	 *
	 * @return array An array of global variables
	 */
	public function getGlobals()
	{
		$globals['blx'] = new BlxVariable();

		if (blx()->getIsInstalled())
		{
			$globals['siteName'] = Blocks::getSiteName();
			$globals['siteUrl'] = Blocks::getSiteUrl();

			if (($user = blx()->accounts->getCurrentUser()) !== null)
				$globals['userName'] = $user->getFullName();
		}

		return $globals;
	}

	/**
	 * Returns the name of the extension.
	 *
	 * @return string The extension name
	 */
	public function getName()
	{
		return 'blocks';
	}
}
