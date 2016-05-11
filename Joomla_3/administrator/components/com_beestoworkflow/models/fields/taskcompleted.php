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

JFormHelper::loadFieldClass('list');

class JFormFieldTaskCompleted extends JFormFieldList {

	public function getInput() {

		$completed  = $this->form->getValue('completed');
		
		if ($completed == 'completed') {
			return JText::_('JYES');
		} else {
			return JText::_('JNO');
		}

	}
	
}
?>
