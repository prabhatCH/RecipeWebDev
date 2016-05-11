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

class JFormFieldParamsstaff extends JFormFieldList {

	public function getInput() {
		
		$id		= $this->form->getValue('id');
		$db		= JFactory::getDbo();
		
		$query 	= ' SELECT a.params ' .
				  ' FROM #__beestowf_users AS a ' .
				  ' WHERE a.id = ' . (int) $id .
				  ' LIMIT 1 ';
				  
		$db->setQuery($query);
		$params = $db->loadResult();
		$registry = new JRegistry();
		$registry->loadString( $params );
		$params2 = $registry->get('params');
		$select	 = array('inherited'=>JText::_('COM_BEESTOWORKFLOW_USERS_INHERITED'),'allowed'=>JText::_('COM_BEESTOWORKFLOW_USERS_ALLOWED'),'denied'=>JText::_('COM_BEESTOWORKFLOW_USERS_DENIED'));
		
		$html = array();	
		
		$html[] = '<table class="table">';
		$html[] = '	<tbody>';
		$html[] = '		<tr>';
		$html[] = '			<td width="85%">'.JText::_('COM_BEESTOWORKFLOW_USERS_ACTIVE_PROJECTS_VIEW').'</td>';
		$html[] = '			<td width="15%">';
		$html[] = '			<select name="params[viewActiveProjects]">';
		$html[] = 			 JHtml::_('select.options', $select  , 'value', 'text', isset($params2->viewActiveProjects) ? $params2->viewActiveProjects : 'inherited');
		$html[] = '			</select>';
		$html[] = '			</td>';
		$html[] = '		</tr>';
		
		$html[] = '		<tr>';
		$html[] = '			<td width="85%">'.JText::_('COM_BEESTOWORKFLOW_USERS_ACTIVE_PROJECTS_CHANGE').'</td>';
		$html[] = '			<td width="15%">';
		$html[] = '			<select name="params[editActiveProjects]">';
		$html[] = 			 JHtml::_('select.options', $select  , 'value', 'text', isset($params2->editActiveProjects) ? $params2->editActiveProjects : 'inherited');
		$html[] = '			</select>';
		$html[] = '			</td>';
		$html[] = '		</tr>';
		
		$html[] = '		<tr>';
		$html[] = '			<td width="85%">'.JText::_('COM_BEESTOWORKFLOW_USERS_PROJECT_ARCHIVE_VIEW').'</td>';
		$html[] = '			<td width="15%">';
		$html[] = '			<select name="params[viewArchiveProjects]">';
		$html[] = 			 JHtml::_('select.options', $select  , 'value', 'text', isset($params2->viewArchiveProjects) ? $params2->viewArchiveProjects : 'inherited');
		$html[] = '			</select>';
		$html[] = '			</td>';
		$html[] = '		</tr>';
		
		$html[] = '		<tr>';
		$html[] = '			<td width="85%">'.JText::_('COM_BEESTOWORKFLOW_USERS_PROJECT_ARCHIVE_EDIT').'</td>';
		$html[] = '			<td width="15%">';
		$html[] = '			<select name="params[editArchiveProjects]">';
		$html[] = 			 JHtml::_('select.options', $select  , 'value', 'text', isset($params2->editArchiveProjects) ? $params2->editArchiveProjects : 'inherited');
		$html[] = '			</select>';
		$html[] = '			</td>';
		$html[] = '		</tr>';
		
		$html[] = '		<tr>';
		$html[] = '			<td width="85%">'.JText::_('COM_BEESTOWORKFLOW_USERS_FILES_VIEW').'</td>';
		$html[] = '			<td width="15%">';
		$html[] = '			<select name="params[viewFiles]">';
		$html[] = 			 JHtml::_('select.options', $select  , 'value', 'text', isset($params2->viewFiles) ? $params2->viewFiles : 'inherited');
		$html[] = '			</select>';
		$html[] = '			</td>';
		$html[] = '		</tr>';
		
		$html[] = '		<tr>';
		$html[] = '			<td width="85%">'.JText::_('COM_BEESTOWORKFLOW_USERS_FILES_DELETE').'</td>';
		$html[] = '			<td width="15%">';
		$html[] = '			<select name="params[deleteFiles]">';
		$html[] = 			 JHtml::_('select.options', $select  , 'value', 'text', isset($params2->deleteFiles) ? $params2->deleteFiles : 'inherited');
		$html[] = '			</select>';
		$html[] = '			</td>';
		$html[] = '		</tr>';
		
		
		$html[] = '		<tr>';
		$html[] = '			<td width="85%">'.JText::_('COM_BEESTOWORKFLOW_USERS_CAN_CREATE_WORKFLOWS').'</td>';
		$html[] = '			<td width="15%">';
		$html[] = '			<select name="params[createWorkflows]">';
		$html[] = 			 JHtml::_('select.options', $select  , 'value', 'text', isset($params2->createWorkflows) ? $params2->createWorkflows : 'inherited');
		$html[] = '			</select>';
		$html[] = '			</td>';
		$html[] = '		</tr>';
		
		
		$html[] = '		<tr>';
		$html[] = '			<td width="85%">'.JText::_('COM_BEESTOWORKFLOW_USERS_CAN_MANAGE_PROJECT_REQUESTS').'</td>';
		$html[] = '			<td width="15%">';
		$html[] = '			<select name="params[manageProjectRequests]">';
		$html[] = 			 JHtml::_('select.options', $select  , 'value', 'text', isset($params2->manageProjectRequests) ? $params2->manageProjectRequests : 'inherited');
		$html[] = '			</select>';
		$html[] = '			</td>';
		$html[] = '		</tr>';
		
		$html[] = '	</tbody>';
		$html[] = '</table>';

		return implode($html);
	}
}
?>
