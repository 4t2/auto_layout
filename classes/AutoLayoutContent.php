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

class AutoLayoutContent extends \ContentElement
{
	protected $strTemplate = 'autolayout_simple';

	public function generateAutoLayout()
	{
		global $autoLayout;

		if ($autoLayout->template != '')
		{
			$this->strTemplate = $autoLayout->template;
		}

		return parent::generate();		
	}

	public function generate()
	{
		global $autoLayout, $autoLayoutContent, $autoLayoutCount, $autoLayoutPos, $autoLayoutId, $autoLayoutElements;

		$autoLayoutElements = array();
		$autoLayoutContent = array();
		$autoLayoutCount = 0;
		$autoLayoutPos = 0;
		$autoLayoutId = $this->id;

		$autoLayout = AutoLayoutModel::findPublishedById($this->autoLayoutSet);

		if ($autoLayout == NULL)
		{
			if (TL_MODE == 'BE')
			{
				return 'AutoLayout ' . $this->id . ' not found!';
			}

			return '';
		}

		/**
		 * Elemente ermitteln
		 */
		$objDatabase = \Database::getInstance();

		$objResult = $objDatabase->prepare("SELECT `id`, `type`, `headline`, `autoLayoutSkip`, `cssID`, `invisible`, `start`, `stop` FROM `tl_content` WHERE `pid` = ? AND `sorting` > ? ORDER BY `sorting`")
			->execute($this->pid, $this->sorting);

		$strBuffer = $autoLayout->layout;

		$intRow = 0;

		while ($objResult->next())
		{
			$blnHiddenElement = $objResult->invisible || ($objResult->start != '' && $objResult->start > time()) || ($objResult->stop != '' && $objResult->stop < time());
			$blnValidElement = $this->autoLayoutPreserveHidden || !$blnHiddenElement;

			if (!$blnHiddenElement && ($objResult->type == 'auto_layout' || $objResult->type == 'auto_layout_end'))
			{
				break;
			}

			if ($blnValidElement && $objResult->type != 'auto_layout' && $objResult->type != 'auto_layout_end')
			{
				if ($blnHiddenElement && !\Input::cookie('FE_PREVIEW'))
				{
					$strPlaceholder = '';
				}
				else
				{
					$strPlaceholder = '{{AL::'.$objResult->id.'}}';
					$autoLayoutCount++;
				}

				if ($objResult->autoLayoutSkip)
				{
					$strPlaceholder .= '{{CE}}';
				}

				$strBuffer = preg_replace('#\{\{CE:?:?([^\}:]*)}}#si', $strPlaceholder, $strBuffer, 1, $count);

				$cssID = unserialize($objResult->cssID);
				$headline = unserialize($objResult->headline);

				// kein Ergebnis ersetzt, also Spalte zu Ende
				if ($count == 0)
				{
					if ($this->autoLayoutRepeat)
					{
						$strBuffer = str_ireplace('{{ROW}}', $intRow+1, $strBuffer);
						$autoLayoutContent[] = $strBuffer;

						$strBuffer = $autoLayout->layout;

						$strBuffer = preg_replace('#<!-- AUTOLAYOUT FIXED START -->.*<!-- AUTOLAYOUT FIXED END -->#siU', '', $strBuffer);
						$strBuffer = preg_replace('#\{\{CE:?:?([^\}:]*)}}#si', $strPlaceholder, $strBuffer, 1);

						$intRow++;
					}
					else
					{
						$autoLayoutCount--;

						break;
					}
				}

				$autoLayoutElements[$objResult->id] = (Object)array
				(
				 	'id'		=> $objResult->id,
				 	'type'		=> $objResult->type,
				 	'headline'	=> $headline['value'],
				 	'cssID'		=> $cssID[0],
				 	'cssClass'	=> $cssID[1],
				 	'row'		=> $intRow
				);
			}
		}

		$strBuffer = str_ireplace('{{ROW}}', $intRow+1, $strBuffer);
		$autoLayoutContent[] = preg_replace('#\{\{CE:?:?([^\}:]*)}}#si', '', $strBuffer);

		if (TL_MODE == 'BE')
		{
			if ($this->invisible)
			{
				$objTemplate = new \BackendTemplate('be_wildcard');
			}
			else
			{
				$objTemplate = new \BackendTemplate('be_autolayout_start');
			}

			$objTemplate->wildcard = '### AUTOLAYOUT ### START ###';
			$objTemplate->title = $autoLayout->title;
			$objTemplate->id = $this->id;

			return $objTemplate->parse();
		}

		return '';
	}


	/**
	 * Generate the content element
	 */
	protected function compile()
	{
		global $autoLayoutContent, $autoLayoutElements;

		$this->Template->content = implode('', $autoLayoutContent);

		$rowData = array();

		foreach ($autoLayoutContent as $row => $content)
		{
			$rowData[$row]['content'] = $content;
		}

		foreach ($autoLayoutElements as $el)
		{
			$rowData[$el->row]['elements'][] = $el;
		}

		$this->Template->rows = $rowData;
	}

}
