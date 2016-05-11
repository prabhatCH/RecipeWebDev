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

//jimport('joomla.application.component.controllerform');

class BeestoWorkflowControllerStaffStep extends JControllerForm {
	
	protected	$option 		= 'com_beestoworkflow';
	protected 	$text_prefix	= 'COM_BEESTOWORKFLOW_STAFFSTEP';
	
	
	public function add () {

		// Check for request forgeries
		JRequest::checkToken('request') or jexit(JText::_('JINVALID_TOKEN'));
		
		// set project id in case we are coming from project page, if no just set to null to clear any possible data
		$app 			= JFactory::getApplication();
		$workflow_id 	= JRequest::getVar('workflow_id',null,'','int');
		$app->setUserState('com_beestoworkflow.session.workflow', $workflow_id);
		
		parent::add();
	}
	
	
	public function save() {

		// Check for request forgeries
		JRequest::checkToken('request') or jexit(JText::_('JINVALID_TOKEN'));
		
		$app 			= JFactory::getApplication();
		$workflow_id 	= $app->getUserState('com_beestoworkflow.session.workflow');
		$data			= JRequest::getVar('jform',array(),'','array');
	
		if(empty($workflow_id)) {
			$this->setRedirect (JRoute::_('index.php?option=com_beestoworkflow&task=staffworkflows',false));
		}

		
		$model = $this->getModel('StaffStep','BeestoWorkflowModel');
		if ($model->save($data)) {		
			$this->setRedirect (JRoute::_('index.php?option=com_beestoworkflow&task=staffworkflow.edit&id=' . $workflow_id,false));
		} else {
			$this->setRedirect (JRoute::_('index.php?option=com_beestoworkflow&task=staffworkflow.edit&id=' . $workflow_id,false), $model->getErrors(), 'warning');
		}
	}
	
	
	public function cancel() {

		// Check for request forgeries
		JRequest::checkToken('request') or jexit(JText::_('JINVALID_TOKEN'));
		
		$app 			= JFactory::getApplication();
		$workflow_id 	= $app->getUserState('com_beestoworkflow.session.workflow');
		$data			= JRequest::getVar('jform',array(),'','array');
	
		if(empty($workflow_id)) {
			$this->setRedirect (JRoute::_('index.php?option=com_beestoworkflow&task=staffworkflows',false));
		}
		
		$this->setRedirect (JRoute::_('index.php?option=com_beestoworkflow&task=staffworkflow.edit&id=' . $workflow_id,false));

	}
	
	
	public function delete () {
		
		// Check for request forgeries
		JRequest::checkToken('request') or jexit(JText::_('JINVALID_TOKEN'));
		
		$workflow_id 	= JRequest::getVar('workflow_id',null,'','int');
		$ids 	= JRequest::getVar('cid', array(), '', 'array');
		$return = JRequest::getVar('return-url','','','base64');
		
		if($return) {
			$return = base64_decode($return);
		} else {
			$return = JRoute::_('index.php?option=com_beestoworkflow&view=staffworkflows',false);
		}
		
		if ($ids) {	
			$model = $this->getModel('StaffStep','BeestoWorkflowModel');
			$model->deleteStep($ids);
			$error = $model->getErrors();
			if ($error) {
				$this->setRedirect($return, $error ,'message');
			} 
		} 
			
		$this->setRedirect($return);
			
	}
	
	
	
}

?>
