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

class JFormFieldTaskAssignedTo extends JFormFieldList {

	public function getInput() {

		$id  = $this->form->getValue('assigned_to');
		$ids = json_decode($id);
		$html = '';
		foreach ($ids as $value) {
			$html .= '<a href="index.php?option=com_beestoworkflow&task=user.edit&id=' . $value . '"> ' . JFactory::getUser($value)->get('name')  . '</a>, ';
		}
		return $html;
	}
	
}
?>
