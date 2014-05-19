<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2014 Leo Feyer
 *
 * @copyright  Lingo4you 2014
 * @author     Mario Müller <http://www.lingolia.com/>
 * @package    AutoLayout
 * @license    http://opensource.org/licenses/lgpl-3.0.html
 */

namespace AutoLayout;

if (!defined('TL_ROOT')) die('You can not access this file directly!');

class AutoLayoutSeparator extends \ContentElement
{
	public function generate()
	{
		if (TL_MODE == 'BE')
		{
			$objTemplate = new \BackendTemplate('be_wildcard');

			$objTemplate->title = $GLOBALS['autoLayout']->title;
			$objTemplate->id = $this->id;

			return $objTemplate->parse();
		}

		return '';
	}

	protected function compile()
	{
	}
}
