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

class BeestoWorkflowMenu {


	public static function getMenu ( $user = 0 )  {
		
		
		$html = array();
		
		if ($user == 0) {
			$html[] = '<ul class="nav nav-tabs">';
			
			$html[] = '<li '. self::setActive(array('','beestoworkflow')) . '>';
			$html[] = '<a href="'.JRoute::_('index.php?option=com_beestoworkflow', false).'">'.JText::_('COM_BEESTOWORKFLOW_MENU_SUMMARY').'</a>';
			$html[] = '</li>';
			$html[] = '<li '. self::setActive(array('staffprojects','staffproject')) . '>';
			$html[] = '<a href="'.JRoute::_('index.php?option=com_beestoworkflow&view=staffprojects', false).'">'.JText::_('COM_BEESTOWORKFLOW_MENU_PROJECTS').'</a>';
			$html[] = '</li>';
			$html[] = '<li '. self::setActive(array('stafftasks','stafftask')) . '>';
			$html[] = '<a href="'.JRoute::_('index.php?option=com_beestoworkflow&view=stafftasks', false) .'">'.JText::_('COM_BEESTOWORKFLOW_MENU_TASKS').'</a>';
			$html[] = '</li>';
			$html[] = '<li '. self::setActive(array('staffnotes','staffnote')) . '>';
			$html[] = '<a href="'.JRoute::_('index.php?option=com_beestoworkflow&view=staffnotes', false) .'">'.JText::_('COM_BEESTOWORKFLOW_MENU_NOTES').'</a>';
			$html[] = '</li>';
			$html[] = '<li '. self::setActive(array('staffworkflows','staffworkflow','staffstep')) . '>';
			$html[] = '<a href="'.JRoute::_('index.php?option=com_beestoworkflow&view=staffworkflows', false) .'">'.JText::_('COM_BEESTOWORKFLOW_MENU_WORKFLOWS').'</a>';
			$html[] = '</li>';
			$html[] = '<li '. self::setActive(array('staffbookmarks')) . '>';
			$html[] = '<a href="'.JRoute::_('index.php?option=com_beestoworkflow&view=staffbookmarks', false). '">'.JText::_('COM_BEESTOWORKFLOW_MENU_FAVORITES').'</a>';
			$html[] = '</li>';
			$html[] = '<li '. self::setActive(array('staffprofile')) . '>';
			$html[] = '<a href="'.JRoute::_('index.php?option=com_beestoworkflow&view=staffprofile&layout=edit', false) .'">'.JText::_('COM_BEESTOWORKFLOW_MENU_PERSONAL').'</a>';
			$html[] = '</li>';
			
			$html[] = '</ul>';
		
		} elseif ( $user == 1 )  {
			$html[] = '<ul class="nav nav-tabs">';
			
			$html[] = '<li '. self::setActive(array('','beestoworkflow')) . '>';
			$html[] = '<a href="'.JRoute::_('index.php?option=com_beestoworkflow', false).'">' .JText::_('COM_BEESTOWORKFLOW_MENU_SUMMARY').'</a>';
			$html[] = '</li>';
			$html[] = '<li '. self::setActive(array('clientprojects','clientproject')) . '>';
			$html[] = '<a href="'.JRoute::_('index.php?option=com_beestoworkflow&view=clientprojects', false).'">'.JText::_('COM_BEESTOWORKFLOW_MENU_PROJECTS').'</a>';
			$html[] = '</li>';
			$html[] = '<li '. self::setActive(array('clienttasks','clienttask')) . '>';
			$html[] = '<a href="'.JRoute::_('index.php?option=com_beestoworkflow&view=clienttasks', false).'">'.JText::_('COM_BEESTOWORKFLOW_CLIENT_TASKS').'</a>';
			$html[] = '</li>';
			
			$html[] = '</ul>';
		
		}
		
		return implode($html);
	
	}


	protected static function setActive ( $view ) {
		
		$current = JRequest::getVar('view','','','string');
		if (in_array($current,$view)) {
			return 'class="active"';
		}
	}






}
             
?>
