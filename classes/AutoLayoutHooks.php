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
		global $autoLayout, $autoLayoutContent, $autoLayoutCount, $autoLayoutPos, $autoLayoutRender, $autoLayoutId, $autoLayoutElements;

		if ($objElement->type == 'auto_layout')
		{
			$autoLayoutPos = 0;

			return (TL_MODE == 'BE' ? $strBuffer : '');
		}
		elseif (isset($autoLayout) && is_object($autoLayout))
		{
			if ($autoLayoutCount > 0 && isset($autoLayoutElements[$objElement->id]))
			{
				$autoLayoutCount--;

				if (TL_MODE == 'FE')
				{
					$cssID = unserialize($objElement->cssID);
					$intRow = $autoLayoutElements[$objElement->id]->row;

					$autoLayoutElements[$objElement->id]->content = $strBuffer;

					$autoLayoutContent[$intRow] = str_replace('{{AL::'.$objElement->id.'}}', $strBuffer, $autoLayoutContent[$intRow]);

					if ($autoLayoutCount == 0)
					{
						$autoLayoutRender = true;

						$objRow = \ContentModel::findByPk($autoLayoutId);
						$objRow->typePrefix = 'autolayout_';

						$objElement = new AutoLayoutContent($objRow);
						$strBuffer = $objElement->generate();

						return $strBuffer;
					}
					else
					{
						return '';
					}
				}
				else
				{
					$strBuffer = '<div class="ce_text block" style="color:#888;">### '.$autoLayout->placeholder[$autoLayoutPos]['label'].' ###</div>'.$strBuffer;

					if (!$objElement->autoLayoutSkip)
					{
						$autoLayoutPos++;

						if ($autoLayoutPos == count($autoLayout->placeholder))
						{
							$autoLayoutPos = 0;
						}
					}
				}
			}
		}

		return $strBuffer;
	}
}
