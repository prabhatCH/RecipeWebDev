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

jimport('joomla.application.component.controllerform');

class BeestoWorkflowControllerTask extends JControllerForm {
	
	protected	$option 		= 'com_beestoworkflow';
	protected 	$text_prefix	= 'COM_BEESTOWORKFLOW_TASKS';
	
	
	public function delete () {
	
		JRequest::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
		
		$ids = JRequest::getVar('cid', array(), '', 'array');
		
		if ($ids) {	
			$model = $this->getModel();
			$model->deleteTasks($ids);
			$this->setRedirect(JRoute::_('index.php?option=com_beestoworkflow&view=tasks',false), JText::_('COM_BEESTOWORKFLOW_TASKS_SUCCESS_DELETE'),'message');
		}
	}
	
	
	public function deletemessage () {
		
		$id 	= JRequest::getVar('id', '', '', 'int');
		$return = JRequest::getVar('return-url', '', '', 'base64');
		
		if ($id) {	
			$model = $this->getModel();
			$model->deleteMessage($id);
		}
		
		if ($return) {
			$this->setRedirect (base64_decode($return));
		} else {
			$this->setRedirect(JRoute::_('index.php?option=com_beestoworkflow&view=tasks',false));
		}
		
	}
	
	
}

?>
