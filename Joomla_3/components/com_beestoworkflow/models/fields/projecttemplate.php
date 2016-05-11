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

class JFormFieldProjectTemplate extends JFormFieldList {

	public function getInput() {
		
		$db = JFactory::getDbo();
		$layout = JRequest::getVar('isowner','','','int');
		$id = $this->form->getValue('id');
		$status = $this->form->getValue('status');
		$user = JFactory::getUser()->get('id');
	
		// when we want only to show
		if ($layout != 1) {
			
			// if it's new display the select
			if(!$id) {
				
				$query = ' SELECT a.id as value, a.title as text FROM #__beestowf_project_workflow AS a WHERE a.published = 1 AND (a.owner = 0 OR a.owner = ' . $user . ') ORDER BY a.id ASC';
				$db->setQuery($query);
				$workflows = $db->loadObjectList();
				array_unshift($workflows, JHtml::_('select.option', 0, JText::_('COM_BEESTOWORKFLOW_STAFFPROJECTS_GENERAL_PROJECT')));
				$html = '<select name="jform[project_template]">';
				$html .= JHtml::_('select.options', $workflows  , 'value', 'text', $this->form->getValue('project_template'));
				$html .= '<select>';
				return $html;
			// if it isn't new do not display the select
			} else {
				
				$workflow = $this->form->getValue('project_template');
				if (!$workflow) {
					return JText::_('COM_BEESTOWORKFLOW_STAFFPROJECTS_GENERAL_PROJECT');
				} else {		
					$query = ' SELECT a.title FROM #__beestowf_project_workflow AS a WHERE a.id = '.$workflow.' LIMIT 1 ';
					$db->setQuery($query);
					$title = $db->loadResult();
					return $title;
				}
			}
			
		// when we allow to edit details	
		} elseif ($layout == 1) {
			
			// if is pending (client request) we will display the select 
			// pending = (id != 0 && status == 0)
			if($id && !$status) {
				
				$query = ' SELECT a.id as value, a.title as text FROM #__beestowf_project_workflow AS a WHERE a.published = 1 AND (a.owner = 0 OR a.owner = ' . $user . ') ORDER BY a.id ASC';
				$db->setQuery($query);
				$workflows = $db->loadObjectList();
				array_unshift($workflows, JHtml::_('select.option', 0, JText::_('COM_BEESTOWORKFLOW_STAFFPROJECTS_GENERAL_PROJECT')));
				$html = '<select name="jform[project_template]">';
				$html .= JHtml::_('select.options', $workflows  , 'value', 'text', $this->form->getValue('project_template'));
				$html .= '<select>';
				return $html;
			// if it's not pending, we don't allow to select the workflow, he should do this when creating
			} else {
				
				$workflow = $this->form->getValue('project_template');
				if (!$workflow) {
					return JText::_('COM_BEESTOWORKFLOW_STAFFPROJECTS_GENERAL_PROJECT');
				} else {		
					$query = ' SELECT a.title FROM #__beestowf_project_workflow AS a WHERE a.id = '.$workflow.' LIMIT 1 ';
					$db->setQuery($query);
					$title = $db->loadResult();
					return $title;
				}
				
			}
		}
		


	}
	
}
?>
