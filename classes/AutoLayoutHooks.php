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
		global $autoLayout, $autoLayoutContent, $autoLayoutCount, $autoLayoutPos, $autoLayoutRowPos, $autoLayoutRender, $autoLayoutId, $autoLayoutElements;

		if ($objElement->type == 'auto_layout')
		{
			$autoLayoutPos = 0;
			$autoLayoutRowPos = 1;

			return (TL_MODE == 'BE' ? $strBuffer : '');
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
					$strBuffer = sprintf('<div class="auto_layout_wrapper%s"><div class="auto_layout_label">AutoLayout :: %s [%s]</div><div class="auto_layout_content">%s</div></div>',
						($autoLayoutPos == 0 && $autoLayoutRowPos > 1 ? ' break' : ''),
						$autoLayout->placeholder[$autoLayoutPos]['label'],
						$autoLayoutRowPos,
						$strBuffer
					);

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
		}

		return $strBuffer;
	}
}
