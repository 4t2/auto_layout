<?php

class AutoLayoutRunonceJob extends \Controller
{
	public function run()
	{
		$objDatabase = \Database::getInstance();

		$objDatabase->prepare("UPDATE `tl_content` SET `type` = ? WHERE `type` = ?")->execute('autoLayoutStart', 'auto_layout');
		$objDatabase->prepare("UPDATE `tl_content` SET `type` = ? WHERE `type` = ?")->execute('autoLayoutStop', 'auto_layout_end');
	}
}

$objAutoLayoutRunonceJob = new AutoLayoutRunonceJob();
$objAutoLayoutRunonceJob->run();
