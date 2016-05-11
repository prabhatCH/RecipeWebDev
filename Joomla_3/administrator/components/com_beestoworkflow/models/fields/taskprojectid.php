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

class JFormFieldTaskProjectId extends JFormFieldList {

	public function getInput() {

		$table 	= JTable::getInstance('Project', 'BeestoWorkflowTable', array());
		$id	 	= $this->form->getValue('project_id');
		$table->load($id);
		
		$html = '<a href="index.php?option=com_beestoworkflow&task=project.edit&id=' . $id . '"> ' . $table->name  . '</a>';

		return $html;
	}
	
}
?>
