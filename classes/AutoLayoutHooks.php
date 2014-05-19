<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2014 Leo Feyer
 *
 * @copyright  Lingo4you 2014
 * @author     Mario Müller <http://www.lingolia.com/>
 * @version    1.0.1
 * @package    AutoLayout
 * @license    http://opensource.org/licenses/lgpl-3.0.html
 */

namespace AutoLayout;

if (!defined('TL_ROOT')) die('You can not access this file directly!');

class AutoLayoutHooks extends \Frontend
{
	public function getContentElementHook($objElement, $strBuffer)
	{
		global $autoLayout, $autoLayoutContent, $autoLayoutCount, $autoLayoutPos, $autoLayoutRowPos, $autoLayoutId, $autoLayoutElements, $autoLayoutSeparator;

		if ($objElement->type == 'autoLayoutStart')
		{
			$autoLayoutPos = 0;
			$autoLayoutRowPos = 1;

			return (TL_MODE == 'BE' ? $strBuffer : '');
		}
		elseif ($objElement->type == 'autoLayoutSeparator')
		{
			$autoLayoutSeparator = true;
		}
		elseif (isset($autoLayout) && is_object($autoLayout))
		{
			if (isset($autoLayoutElements[$objElement->id]))
			{
				$autoLayoutCount--;

				if (TL_MODE == 'FE')
				{
					$cssID = unserialize($objElement->cssID);
					$intRow = $autoLayoutElements[$objElement->id]->row;

					#$autoLayoutElements[$objElement->id]->content = $strBuffer;

					$autoLayoutContent[$intRow] = str_replace('{{AL::'.$objElement->id.'}}', $strBuffer, $autoLayoutContent[$intRow]);

					if ($autoLayoutCount == 0)
					{
						$objRow = \ContentModel::findByPk($autoLayoutId);
						$objRow->typePrefix = 'autolayout_';

						$objElement = new AutoLayoutStart($objRow);
						$strBuffer = $objElement->generateAutoLayout();

						return $strBuffer;
					}
					else
					{
						return '';
					}
				}
				else
				{
					$arrLabels = array('<span><strong>AutoLayout</strong></span>');

					$arrLabels[] = sprintf('<span>%s :: %s</span>', $GLOBALS['TL_LANG']['tl_content']['autoLayoutElement'], ($autoLayout->placeholder[$autoLayoutPos]['label'] ?: '#'.($autoLayoutPos+1)));

					if ($objElement->autoLayoutSkip)
					{
						$arrLabels[] = ' <span>&radic; '.$GLOBALS['TL_LANG']['tl_content']['autoLayoutSkip'][0].'</span>';
					}

					if (!$autoLayoutSeparator && $autoLayoutPos == 0 && $autoLayoutRowPos > 1)
					{
						$arrLabels[] = ' <span>&crarr; '.$GLOBALS['TL_LANG']['tl_content']['autoLayoutBreak'].'</span>';
					}

					$strBuffer = '<div class="auto_layout_labels">'.implode(' ', $arrLabels).'</div>'.$strBuffer;

					if (!$objElement->autoLayoutSkip)
					{
						$autoLayoutPos++;

						if ($autoLayoutPos == count($autoLayout->placeholder))
						{
							$autoLayoutPos = 0;
							$autoLayoutRowPos++;
						}
					}
				}
			}

			$autoLayoutSeparator = false;
		}

		return $strBuffer;
	}
}
