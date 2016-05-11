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

class JFormFieldType extends JFormFieldList {

	public function getInput() {
		
		$type = array ('0'=>JText::_('COM_BEESTOWORKFLOW_USERS_STAFF'), '1'=>JText::_('COM_BEESTOWORKFLOW_USERS_CLIENT'));
		
		$result = $this->form->getValue('type');
		
		$html = '<input type="text" size="30" class="inputbox" value="' . $type[$result] . '" id="jform_types" name="jform[types]" readonly="readonly" />';

		return $html;
		

	}
	
}
?>
