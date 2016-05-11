<?php
/*
 * @component BeestoWorkflow
 * @version 1.4 "Erdos"
 * @website : http://www.ionutlupu.me
 * @copyright Ionut Lupu. All rights reserved.
 * @license : http://www.gnu.org/copyleft/gpl.html GNU/GPL , see license.txt
 */
 
 
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');
$layout 	= JRequest::getVar('isowner','','','int');

if ($this->item->id && $layout == 1) {
	echo $this->loadTemplate('add');
} elseif ($this->item->id && $layout!=1) {
	echo $this->loadTemplate('view');
} else {
	echo $this->loadTemplate('add');
}


?>
