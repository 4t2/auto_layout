<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

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

$GLOBALS['TL_DCA']['tl_content']['fields']['autoLayoutSkip'] = array
(
	'label'				=> &$GLOBALS['TL_LANG']['tl_content']['autoLayoutSkip'],
	'exclude'			=> true,
	'inputType'			=> 'checkbox',
	'default'			=> '',
	'eval'				=> array
	(
		'tl_class'		=> 'w50'
	),
	'sql'				=> "char(1) NOT NULL default ''"
);


$strPalette = '{auto_layout_legend},autoLayoutSkip';

foreach ($GLOBALS['TL_DCA']['tl_content']['palettes'] as $key => $palette)
{
	if (!is_array($palette))
	{
		if (strpos($palette, '{expert_legend:hide}') !== FALSE)
		{
			$GLOBALS['TL_DCA']['tl_content']['palettes'][$key] = str_replace('{expert_legend:hide}', $strPalette.';{expert_legend:hide}', $palette);
		}
		elseif (strpos($palette, '{protected_legend:hide}') !== FALSE)
		{
			$GLOBALS['TL_DCA']['tl_content']['palettes'][$key] = str_replace('{protected_legend:hide}', $strPalette.';{protected_legend:hide}', $palette);
		}
		else
		{
			$GLOBALS['TL_DCA']['tl_content']['palettes'][$key] .= ';'.$strPalette;
		}
	}
}



#$GLOBALS['TL_DCA']['tl_content']['palettes']['__selector__'][] = 'autoLayoutSet';
$GLOBALS['TL_DCA']['tl_content']['palettes']['auto_layout'] = '{type_legend},type,headline;{layout_legend},autoLayoutSet,autoLayoutRepeat,autoLayoutPreserveHidden;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID,space';

$GLOBALS['TL_DCA']['tl_content']['palettes']['auto_layout_end'] = '{type_legend},type';

$GLOBALS['TL_DCA']['tl_content']['fields']['autoLayoutSet'] = array
(
	'label'				=> &$GLOBALS['TL_LANG']['tl_content']['autoLayoutSet'],
	'exclude'			=> true,
	'inputType'			=> 'select',
	'foreignKey'		=> 'tl_auto_layout.title',
	'eval'				=> array
	(
		'mandatory'				=> true,
		'submitOnChange'		=> true,
		'includeBlankOption'	=> false,
		'tl_class'				=> 'clr'
	),
	'sql'				=> "int(10) unsigned NOT NULL default '0'"
);

$GLOBALS['TL_DCA']['tl_content']['fields']['autoLayoutRepeat'] = array
(
	'label'				=> &$GLOBALS['TL_LANG']['tl_content']['autoLayoutRepeat'],
	'exclude'			=> true,
	'inputType'			=> 'checkbox',
	'default'			=> '1',
	'eval'				=> array
	(
		'tl_class'		=> 'w50 m12'
	),
	'sql'				=> "char(1) NOT NULL default '1'"
);

$GLOBALS['TL_DCA']['tl_content']['fields']['autoLayoutPreserveHidden'] = array
(
	'label'				=> &$GLOBALS['TL_LANG']['tl_content']['autoLayoutPreserveHidden'],
	'exclude'			=> true,
	'inputType'			=> 'checkbox',
	'default'			=> '',
	'eval'				=> array
	(
		'tl_class'		=> 'w50 m12'
	),
	'sql'				=> "char(1) NOT NULL default ''"
);

