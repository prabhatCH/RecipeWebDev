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

class BeestoWorkflowControllerStaffWorkflow extends JControllerForm {
	
	protected	$option 		= 'com_beestoworkflow';
	protected 	$text_prefix	= 'COM_BEESTOWORKFLOW_STAFFWORKFLOWS';
	
	public function getModel($name = 'StaffWorkflow', $prefix = 'BeestoWorkflowModel', $config = array('ignore_request' => true)) {
		$model = parent::getModel($name, $prefix, $config);
		return $model;
	}
	

	public function orderdown () {
		
		JRequest::checkToken('request') or jexit(JText::_('JINVALID_TOKEN'));
		
		$cb 	= JRequest::getVar('cid',array(),'post','array');
		$return = JRequest::getVar('return-url','','post','base64');
		
		if($return) {
			$return = base64_decode($return);
		} else {
			$return = JRoute::_('index.php?option=com_beestoworkflow&view=staffworkflows',false);
		}
		
		if ($cb) {
			$model = $this->getModel();
			
			if ($model->orderdown()) {
				$this->setRedirect($return);
			} else {
				$this->setRedirect($return, $model->getErrors(), 'warning');
			}
		}
		
		$this->setRedirect($return);
	}
	
	
	public function orderup () {
		
		JRequest::checkToken('request') or jexit(JText::_('JINVALID_TOKEN'));
		
		$cb 	= JRequest::getVar('cid',array(),'post','array');
		$return = JRequest::getVar('return-url','','post','base64');
		
		if($return) {
			$return = base64_decode($return);
		} else {
			$return = JRoute::_('index.php?option=com_beestoworkflow&view=staffworkflows',false);
		}
		
		if ($cb) {
			$model = $this->getModel();
			
			if ($model->orderup()) {
				$this->setRedirect($return);
			} else {
				$this->setRedirect($return, $model->getErrors(), 'warning');
			}
		}
		
		$this->setRedirect($return);
	}
	
	
	public function edit () {
		
		// set project id in case we are coming from project page, if no just set to null to clear any possible data
		$app 			= JFactory::getApplication();
		$workflow_id 	= JRequest::getVar('id',null,'','int');
		$app->setUserState('com_beestoworkflow.session.workflow', $workflow_id);
		
		parent::edit();
	}
	
	
}

?>
