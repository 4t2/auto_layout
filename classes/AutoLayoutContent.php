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

	protected $strTemplate = 'autolayout';

	protected $arrElements;

	/**
	 * Parse the template
	 * @return string
	 */
	public function generate()
	{
		global $autoLayout, $autoLayoutContent, $autoLayoutCount;

		$autoLayoutContent = '';
		$autoLayoutCount = 0;

		$autoLayout = AutoLayoutModel::findPublishedById($this->autoLayoutSet);

		if ($autoLayout == NULL)
		{
			if (TL_MODE == 'BE')
			{
				return 'AutoLayout ' . $this->id . ' not found!';
			}

			return '';
		}

		if (TL_MODE == 'BE')
		{
			$objTemplate = new \BackendTemplate('be_wildcard');

			$objTemplate->wildcard = '### AUTO GRID ###';
			$objTemplate->title = $autoLayout->title;
			$objTemplate->id = $this->id;

			return $objTemplate->parse();
		}

		if ($autoLayout->template != '')
		{
			$this->strTemplate = $autoLayout->template;
		}

		/**
		 * Elemente ermitteln
		 */
		$objDatabase = \Database::getInstance();

		$objResult = $objDatabase->prepare("SELECT `id`, `type`, `headline`, `autoLayoutSkip`, `cssID`, `invisible`, `start`, `stop` FROM `tl_content` WHERE `pid` = ? AND `sorting` > ?")
			->execute($this->pid, $this->sorting);


		$strBuffer = $autoLayout->layout;

		$this->arrElements = array();
		$intRow = 1;

		while ($objResult->next())
		{
			$blnHiddenElement = $objResult->invisible || ($objResult->start != '' && $objResult->start > time()) || ($objResult->stop != '' && $objResult->stop < time());
			$blnValidElement = $this->autoLayoutPreserveHidden || !$blnHiddenElement;

			if ($objResult->type == 'auto_layout' || $objResult->type == 'auto_layout_end')
			{
				break;
			}
			elseif ($blnValidElement)
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

				if ($count == 0)
				{
					if ($this->autoLayoutRepeat)
					{
						$autoLayoutContent .= str_ireplace('{{ROW}}', $intRow, $strBuffer);
						$strBuffer = $autoLayout->layout;
						$strBuffer = preg_replace('#\{\{CE:?:?([^\}:]*)}}#si', $strPlaceholder, $strBuffer, 1);

						$intRow++;
					}
					else
					{
						break;
					}
				}

				$this->arrElements[] = (Object)array
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

		$strBuffer = str_ireplace('{{ROW}}', $intRow, $strBuffer);
		$autoLayoutContent .= preg_replace('#\{\{CE:?:?([^\}:]*)}}#si', '', $strBuffer);

#die(htmlspecialchars($autoLayoutContent));

		return parent::generate();
	}

	/**
	 * Generate the content element
	 */
	protected function compile()
	{
		$this->Template->elements = $this->arrElements;
		$this->Template->content = '[[AUTOLAYOUT]]';
	}

}
