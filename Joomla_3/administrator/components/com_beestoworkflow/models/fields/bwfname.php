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

class JFormFieldBwfname extends JFormFieldList {

	public function getInput() {
		
		$id = $this->form->getValue('id');
		$name = JFactory::getUser($id)->get('name');
		
		$html = '<input type="text" class="input-medium pull-left" value="' . $name . '" id="jform_name" name="jform[name]" readonly="readonly" />&nbsp;
				<span class="pull-left"><a class="btn" href="index.php?option=com_users&task=user.edit&id='.$id.'">' . JText::_('COM_BEESTOWORKFLOW_USERS_EDIT') . '</a></span>';

		return $html;

	}
	
}
?>
