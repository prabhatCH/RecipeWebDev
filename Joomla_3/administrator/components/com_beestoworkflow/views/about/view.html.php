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

//jimport( 'joomla.application.component.view');

class BeestoWorkflowViewAbout extends JViewLegacy {
	
	public function display($tpl = null) {
		$this->addToolbar();
		parent::display($tpl);
	}
	

	public function addToolbar() {	
		JHtml::stylesheet( 'administrator/components/com_beestoworkflow/assets/css/default.css' );

		$canDo	= BeestoWorkflowHelper::getActions();
		
		JToolBarHelper::title(   'beestoworkflow :: ' . JText::_( 'COM_BEESTOWORKFLOW_ABOUT' ), 'info' );	
		
		if ($canDo->get('core.admin')) {
			JToolBarHelper::preferences('com_beestoworkflow');
			JToolBarHelper::divider();
		}		
	}
	
}
?>
