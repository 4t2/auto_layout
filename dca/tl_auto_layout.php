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


/**
 * Table tl_auto_layout
 */
$GLOBALS['TL_DCA']['tl_auto_layout'] = array
(

	// Config
	'config' => array
	(
		'dataContainer'               => 'Table',
		'enableVersioning'            => true,
		'sql' => array
		(
			'keys' => array
			(
				'id' => 'primary'
			)
		)
	),

	// List
	'list' => array
	(
		'sorting' => array
		(
			'mode'                    => 1,
			'fields'                  => array('title'),
			'flag'                    => 1
		),
		'label' => array
		(
			'fields'                  => array('title'),
			'format'                  => '%s'
		),
		'global_operations' => array
		(
			'all' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['MSC']['all'],
				'href'                => 'act=select',
				'class'               => 'header_edit_all',
				'attributes'          => 'onclick="Backend.getScrollOffset();" accesskey="e"'
			)
		),
		'operations' => array
		(
			'edit' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_auto_layout']['edit'],
				'href'                => 'act=edit',
				'icon'                => 'edit.gif'
			),
			'copy' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_auto_layout']['copy'],
				'href'                => 'act=copy',
				'icon'                => 'copy.gif'
			),
			'delete' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_auto_layout']['delete'],
				'href'                => 'act=delete',
				'icon'                => 'delete.gif',
				'attributes'          => 'onclick="if(!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\'))return false;Backend.getScrollOffset()"'
			),
			'show' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_auto_layout']['show'],
				'href'                => 'act=show',
				'icon'                => 'show.gif'
			)
		)
	),

	// Select
	'select' => array
	(
		'buttons_callback' => array()
	),

	// Edit
	'edit' => array
	(
		'buttons_callback' => array()
	),

	// Palettes
	'palettes' => array
	(
#		'__selector__'                => array(''),
		'default'                     => '{title_legend},title;{layout_legend},layout,template;'
	),

	// Subpalettes
	'subpalettes' => array
	(
#		''                            => ''
	),

	// Fields
	'fields' => array
	(
		'id' => array
		(
			'sql'				=> "int(10) unsigned NOT NULL auto_increment"
		),
		'tstamp' => array
		(
			'sql'				=> "int(10) unsigned NOT NULL default '0'"
		),
		'title' => array
		(
			'label'				=> &$GLOBALS['TL_LANG']['tl_auto_layout']['title'],
			'exclude'			=> true,
			'inputType'			=> 'text',
			'eval'				=> array
			(
			 	'mandatory'			=> true,
			 	'maxlength'			=> 255,
			 	'class'				=> 'long'
			 ),
			'sql'				=> "varchar(255) NOT NULL default ''"
		),
		'layout' => array
		(
			'label'				=> &$GLOBALS['TL_LANG']['tl_auto_layout']['layout'],
			'inputType'			=> 'textarea',
			'exclude'			=> true,
			'eval'				=> array
			(
			 	'allowHtml'			=> true,
			 	'class'				=> 'monospace',
			 	'rte'				=> 'ace|html',
			 	'helpwizard'		=> true
			),
			'explanation'		=> 'autoLayout',
			'sql'				=> "text NULL"
		),
		'template' => array
		(
			'label'				=> &$GLOBALS['TL_LANG']['tl_auto_layout']['template'],
			'exclude'			=> true,
			'filter'			=> true,
			'search'			=> true,
			'inputType'			=> 'select',
			'options_callback'	=> array('tl_auto_layout', 'getLayoutTemplates'),
			'sql'				=> "varchar(255) NOT NULL default ''"
		)
	)
);



class tl_auto_layout extends Backend
{

	/**
	 * Import the back end user object
	 */
	public function __construct()
	{
		parent::__construct();
		$this->import('BackendUser', 'User');
	}

	/**
	 * Return all Foundation templates as array
	 * @return array
	 */
	public function getLayoutTemplates()
	{
		return $this->getTemplateGroup('autolayout_');
	}

}
