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

class AutoLayoutModel extends \Model
{
	protected static $strTable = 'tl_auto_layout';


	public static function findPublishedById($intId, array $arrOptions=array())
	{
		$t = static::$strTable;
		$arrColumns = array("$t.id=?");

		$objElement = static::findOneBy($arrColumns, $intId, $arrOptions);

		if ($objElement != null)
		{
			$strLayout = $objElement->layout;

			$arrPlaceholder = array();

			if (preg_match_all('#\{\{ce:?:?([^\}:]*)}}#si', $objElement->layout, $matches, PREG_OFFSET_CAPTURE | PREG_SET_ORDER))
			{
				$count = 1;

				foreach ($matches as $match)
				{
					$placeholder = array
					(
					 	'offset'	=> $match[0][1],
					 	'length'	=> strlen($match[0][0]),
					 	'label'		=> trim($match[1][0].' ['.$count++.']')
					);

					$arrPlaceholder[] = $placeholder;
				}
			}

			$objElement->placeholder = $arrPlaceholder;
			$objElement->count = count($arrPlaceholder);
		}

		return $objElement;
	}
}
