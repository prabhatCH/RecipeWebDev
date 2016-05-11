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
jimport('joomla.utilities.date');

class JFormFieldProjectStatus extends JFormFieldList {

	public function getInput() {
		
		$stage 		= array(0=>JText::_('COM_BEESTOWORKFLOW_PROJECTS_PENDING'),1=>JText::_('COM_BEESTOWORKFLOW_PROJECTS_ACTIVE'),2=>JText::_('COM_BEESTOWORKFLOW_PROJECTS_COMPLETED'));
		$due 		= strtotime($this->form->getValue('due'));
		$status 	= $this->form->getValue('status');
		
		//get current date
		$tz		= new DateTimeZone(JFactory::getApplication()->getCfg('offset'));
		$jdate 	= new JDate();
		$jdate->setTimezone($tz);
		$current = $jdate->toSql(true);
		$current = strtotime($current);
		
		if (($status != 1) && ( $current > $due)) {
			$overdue = JText::_('COM_BEESTOWORKFLOW_PROJECTS_OVERDUE');
		} else {
			$overdue = '';
		}
	
		
		$html = '<input type="text" readonly="readonly" class="readonly" value="'.$stage[$status]. ' ' . $overdue .'" id="jform_client" name="jform[status]" aria-invalid="false">';
		
		return $html;

	}
	
}
?>
