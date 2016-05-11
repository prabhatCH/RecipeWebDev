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

class JFormFieldTaskProject extends JFormFieldList {

	public function getInput() {

		$db 	= JFactory::getDbo();
		$layout = JRequest::getVar('isowner','','','int');
		$id 	= $this->form->getValue('id');
		$userID	= JFactory::getUser()->get('id');
	
		// get projects where he is manager or he participates
		$allowed 	= BeestoWorkflowHelper::getOtherProjects ();
		if ( !$allowed ) {
			return false;
		}
		
		if ($allowed == 'all'){
			$query = ' SELECT a.id as value, a.name as text FROM #__beestowf_projects AS a WHERE a.status = 1 ';
			$db->setQuery($query);
			$access = $db->loadObjectList();
		} else {
			$query = ' SELECT a.id as value, a.name as text FROM #__beestowf_projects AS a WHERE a.id IN (' . implode(',',$allowed) . ' ) AND a.status = 1';
			$db->setQuery($query);
			$access = $db->loadObjectList();
		}


		// when we want only to show
		if ($layout != 1) {
			
			// if it's new display the select
			if(!$id) {
				$access ? array_unshift($access, JText::_('COM_BEESTOWORKFLOW_STAFFTASKS_TASK_GENERAL')) : $access[0] = JText::_('COM_BEESTOWORKFLOW_STAFFTASKS_TASK_GENERAL');
				$html = '<select name="jform[project_id]">';
				$html .= JHtml::_('select.options', $access , 'value', 'text', $this->form->getValue('project_id'));
				$html .= '<select>';
				return $html;
			// if it isn't new do not display the select
			} else {
				$project = $this->form->getValue('project_id');
				if ($project) {
					$table = JTable::getInstance('Projects','BeestoWorkflowTable');
					$table->load($project);
					return $table->name;
				}

			}
			
		// when we allow to edit details	
		} elseif ($layout == 1) {
					
			$project = $this->form->getValue('project_id');
			if ($project) {
				$table = JTable::getInstance('Project','BeestoWorkflowTable');
				$table->load($project);
				return $table->name;
			}
		}
	}
	
	
}
?>
