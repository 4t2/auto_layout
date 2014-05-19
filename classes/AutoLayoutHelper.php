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

class AutoLayoutHelper extends \Controller
{
	public static function isAutoLayoutElement($id)
	{
		$autoLayoutElements = array();

		$objDatabase = \Database::getInstance();

		$objAutoLayout = $objDatabase->prepare("SELECT c2.id, c2.sorting, c2.autoLayoutPreserveHidden, al.layout FROM tl_content AS c1, tl_content AS c2, tl_auto_layout AS al WHERE c1.id=? AND c2.type='autoLayoutStart' AND c2.pid=c1.pid AND c2.sorting<c1.sorting AND al.id=c2.autoLayoutSet ORDER BY c2.sorting DESC")->limit(1)->execute($id);

		if (!$objAutoLayout->next())
		{
			return false;
		}

		$placeholderCount = 0;

		$objResult = $objDatabase->prepare("SELECT c2.type, c2.id, c2.invisible, c2.start, c2.stop, c2.autoLayoutSkip FROM tl_content AS c1, tl_content AS c2 WHERE c1.id=? AND c2.sorting>? AND c2.pid=c1.pid AND c2.sorting<=c1.sorting ORDER BY c2.sorting")->execute($id, $objAutoLayout->sorting);

		while ($objResult->next())
		{
			if (strstr($objResult->type, 'autoLayout') !== FALSE && !$objResult->invisible)
			{
				return false;
			}

			if ($objResult->id == $id)
			{
				return true;
			}

			$blnHiddenElement = $objResult->invisible || ($objResult->start != '' && $objResult->start > time()) || ($objResult->stop != '' && $objResult->stop < time());
			$blnValidElement = $objAutoLayout->autoLayoutPreserveHidden || !$blnHiddenElement;

			if ($blnValidElement)
			{
				if ($blnHiddenElement)
				{
					#continue;
				}

				if (!$objResult->autoLayoutSkip)
				{
					$placeholderCount--;
				}
			}
		}

		return false;
	}
}
