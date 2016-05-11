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

class JFormFieldProjectManager extends JFormFieldList {

	public function getInput() {
		
		$id 	= $this->form->getValue('manager');
		$name 	= JFactory::getUser($id)->get('name');
		
		$html = '<input type="text" readonly="readonly" class="readonly" value="'.$name.'" id="jform_client" name="jform[manager]" aria-invalid="false">';
		
		return $html;

	}
	
}
?>
