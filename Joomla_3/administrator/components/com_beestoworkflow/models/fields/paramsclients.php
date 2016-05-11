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

class JFormFieldParamsclients extends JFormFieldList {

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
		
		$html[] = '<table class="adminlist">';
		$html[] = '	<tbody>';
		
		$html[] = '		<tr>';
		$html[] = '			<td width="85%">'.JText::_('COM_BEESTOWORKFLOW_USERS_VIEW_ALL_PROJECTS_WITH_VIEW').'</td>';
		$html[] = '			<td width="15%">';
		$html[] = '			<select name="params[view_allprojects]">';
		$html[] = 			 JHtml::_('select.options', $select  , 'value', 'text', isset($params2->view_allprojects) ? $params2->view_allprojects : 'inherited');
		$html[] = '			</select>';
		$html[] = '			</td>';
		$html[] = '		</tr>';
		
		$html[] = '		<tr>';
		$html[] = '			<td width="85%">'.JText::_('COM_BEESTOWORKFLOW_USERS_VIEW_ALL_TASKS').'</td>';
		$html[] = '			<td width="15%">';
		$html[] = '			<select name="params[view_alltasks]">';
		$html[] = 			 JHtml::_('select.options', $select  , 'value', 'text', isset($params2->view_alltasks) ? $params2->view_alltasks : 'inherited');
		$html[] = '			</select>';
		$html[] = '			</td>';
		$html[] = '		</tr>';
		
		$html[] = '	</tbody>';
		$html[] = '</table>';

		return implode($html);
	}
	
}
?>
