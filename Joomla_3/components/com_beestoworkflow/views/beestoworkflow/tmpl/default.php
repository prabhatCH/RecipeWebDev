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

if ($this->authorised) {
	
	if ( $this->authorised->type == 0 ) {
		echo $this->loadTemplate('staff');
	} elseif ( $this->authorised->type == 1 ) {
		echo $this->loadTemplate('client');
	}
	
}

?>

