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

class BeestoWorkflowControllerClientProject extends JControllerForm {
	
	protected	$option 		= 'com_beestoworkflow';
	protected 	$text_prefix	= 'COM_BEESTOWORKFLOW_CLIENT';
	
	public function getModel($name = 'ClientProject', $prefix = 'BeestoWorkflowModel', $config = array('ignore_request' => true)) {
		$model = parent::getModel($name, $prefix, $config);
		return $model;
	}
	
	
	public function addcomment () {
		
		JRequest::checkToken('request') or jexit(JText::_('JINVALID_TOKEN'));
		$return 	= JRequest::getVar('return-url','','','base64');
		$project 	= JRequest::getVar('project','','','int');
		$comment 	= JRequest::getVar('beestoworkflow-comment','','','string');
		$sendEmail 	= JRequest::getVar('messagealert',0,'','int');

		if($return) {
			$return = base64_decode($return);
		} else {
			$return = JRoute::_('index.php?option=com_beestoworkflow&view=clientprojects',false);
		}
		
		if(!$comment || !$project) {
			$this->setRedirect( $return );
			return false;
		}
		
		$model = $this->getModel();
		if ($model->addComment($project,$comment)) {
			// send email alert if it's selected
			if ($sendEmail) {
				BeestoWorkflowHelper::sendAlert('newprojmessage',array('id'=>$project,'comment'=>$comment));
			}
		}
		$this->setRedirect( $return );
		
	}
	
	
	
	public function deletecomment () {
		
		JRequest::checkToken('request') or jexit(JText::_('JINVALID_TOKEN'));
		$return 	= JRequest::getVar('return-url','','','base64');
		$comments 	= JRequest::getVar('mid',array(),'','array');

		if($return) {
			$return = base64_decode($return);
		} else {
			$return = JRoute::_('index.php?option=com_beestoworkflow&view=clientprojects',false);
		}
		
		if(!$comments) {
			$this->setRedirect( $return );
			return false;
		}

		$model = $this->getModel();
		$model->deleteComment($comments);
		$this->setRedirect( $return );
	}
	
	
	public function addfile () {
	
		JRequest::checkToken('request') or jexit(JText::_('JINVALID_TOKEN'));
		
		$return 	= JRequest::getVar('return-url','','','base64');
		$file		= JRequest::getVar('fileadd', array(), 'files', 'array');
		$project 	= JRequest::getVar('project','','','int');
		$sendEmail 	= JRequest::getVar('filealert',0,'','int');

		if($return) {
			$return = base64_decode($return);
		} else {
			$return = JRoute::_('index.php?option=com_beestoworkflow&view=clientprojects',false);
		}

		if(!$file['name']) {
			$this->setRedirect( $return );
			return false;
		}
		
		$model = $this->getModel();
		
		if (!$model->addfile()) {	
			$error = $model->getError();
			$this->setRedirect( $return,$error,'warning' );
		} else {
			if ($sendEmail) {
				BeestoWorkflowHelper::sendAlert('newprojfile',array('name'=>$file['name'],'project'=>$project));
			}
		}
		
		$this->setRedirect( $return );
	}
	
	
	
}

?>
