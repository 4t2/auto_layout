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

class AutoLayoutHooks extends \Frontend
{
	public function getContentElementHook($objElement, $strBuffer)
	{
		global $autoLayout, $autoLayoutContent, $autoLayoutCount, $autoLayoutWrapper;

		if ($objElement->type == 'auto_layout')
		{
			$autoLayoutWrapper = $strBuffer;
			return '';
		}
		elseif ($autoLayoutCount > 0 && isset($autoLayout) && is_object($autoLayout))
		{
			$cssID = unserialize($objElement->cssID);

			$autoLayoutContent = str_replace('{{AL::'.$objElement->id.'}}', $strBuffer, $autoLayoutContent);
			$autoLayoutContent = preg_replace('#\{\{ID\}\}#si', $objElement->id, $autoLayoutContent, 1);
			$autoLayoutContent = preg_replace('#\{\{CSS_ID\}\}#si', $cssID[0], $autoLayoutContent, 1);
			$autoLayoutContent = preg_replace('#\{\{CSS_CLASS\}\}#si', $cssID[1], $autoLayoutContent, 1);

			$autoLayoutCount--;

			if ($autoLayoutCount == 0)
			{
				$strBuffer = str_replace('[[AUTOLAYOUT]]', $autoLayoutContent, $autoLayoutWrapper).$strBuffer;
			}
			else
			{
				return '';
			}
		}

		return $strBuffer;
	}
}
