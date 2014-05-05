<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2014 Leo Feyer
 *
 * @copyright  Lingo4you 2014
 * @author     Mario Müller <http://www.lingolia.com/>
 * @version    1.0.0
 * @package    AutoGrid
 * @license    http://opensource.org/licenses/lgpl-3.0.html
 */


$GLOBALS['BE_MOD']['design']['auto_layout'] = array
(
	'tables'	=> array('tl_auto_layout'),
    'icon'		=> 'system/modules/auto_layout/assets/icon.png'
);

$GLOBALS['TL_CTE']['layout'] = array
(
	'auto_layout' => 'AutoLayoutContent',
	'auto_layout_end' => 'AutoLayoutEndContent'
);


/**
 * Hooks
 */
$GLOBALS['TL_HOOKS']['getContentElement'][] = array('AutoLayoutHooks', 'getContentElementHook');


$GLOBALS['autoLayoutRender'] = false;
