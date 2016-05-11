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

class JFormFieldTaskCreatedBy extends JFormFieldList {

	public function getInput() {

		$id	 	= $this->form->getValue('created_by');
		
		$html = '<a href="index.php?option=com_beestoworkflow&task=user.edit&id=' . $id . '"> ' . JFactory::getUser($id)->get('name')  . '</a>';

		return $html;
	}
	
}
?>
