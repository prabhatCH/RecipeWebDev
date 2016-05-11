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

//jimport('joomla.application.component.controlleradmin');

class BeestoWorkflowControllerStaffWorkflows extends JControllerAdmin {
	
	protected	$option 		= 'com_beestoworkflow';
	protected 	$text_prefix	= 'COM_BEESTOWORKFLOW_WORKFLOWS';
	
	public function getModel($name = 'StaffWorkflow', $prefix = 'BeestoWorkflowModel', $config = array('ignore_request' => true)) {
		$model = parent::getModel($name, $prefix, $config);
		return $model;
	}
	
	
	public function delete () {
		
		JRequest::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
		
		$ids 	= JRequest::getVar('cid', array(), '', 'array');
		$return = JRequest::getVar('return-url','','','base64');
		
		if($return) {
			$return = base64_decode($return);
		} else {
			$return = JRoute::_('index.php?option=com_beestoworkflow&view=staffworkflows',false);
		}
		
		if ($ids) {	
			$model = $this->getModel();
			$model->delete($ids);
			$error = $model->getError();
			if ($error) {
				$this->setRedirect($return, $error,'warning');
			} else {
				$this->setRedirect($return, JText::_('COM_BEESTOWORKFLOW_WORKFLOWS_WORKFLOWS_DELETED'),'message');
			}	
		}

		$this->setRedirect($return);
	}
	
	
	
	
}

?>
