<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2014 Leo Feyer
 *
 * @copyright  Lingo4you 2014
 * @author     Mario Müller <http://www.lingolia.com/>
 * @version    1.0.0
 * @package    AutoLayout
 * @license    http://opensource.org/licenses/lgpl-3.0.html
 */

namespace AutoLayout;

if (!defined('TL_ROOT')) die('You can not access this file directly!');

class AutoLayoutEndContent extends \ContentElement
{
	public function generate()
	{
		if (TL_MODE == 'BE')
		{
			if ($this->invisible)
			{
				$objTemplate = new \BackendTemplate('be_wildcard');
			}
			else
			{
				$objTemplate = new \BackendTemplate('be_autolayout_end');
			}

			$objTemplate->wildcard = '### AUTOLAYOUT ### END ###';
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
