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


/**
 * Register the namespaces
 */
ClassLoader::addNamespaces(array
(
	'AutoLayout',
));


/**
 * Register the classes
 */
ClassLoader::addClasses(array
(
	// Models
	'AutoLayout\AutoLayoutModel'		=> 'system/modules/auto_layout/models/AutoLayoutModel.php',

	// Elements
	'AutoLayout\AutoLayoutContent'		=> 'system/modules/auto_layout/classes/AutoLayoutContent.php',
	'AutoLayout\AutoLayoutEndContent'		=> 'system/modules/auto_layout/classes/AutoLayoutEndContent.php',

	// Helper
	'AutoLayout\AutoLayoutHelper'		=> 'system/modules/auto_layout/classes/AutoLayoutHelper.php',

	// Hooks
	'AutoLayout\AutoLayoutHooks'		=> 'system/modules/auto_layout/classes/AutoLayoutHooks.php',
));


/**
 * Register the templates
 */
TemplateLoader::addFiles(array
(
	'autolayout_simple' => 'system/modules/auto_layout/templates',
));
