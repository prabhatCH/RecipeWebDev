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

jimport('joomla.application.component.controlleradmin');

class BeestoWorkflowControllerUsers extends JControllerAdmin {
	
	protected	$option 		= 'com_beestoworkflow';
	protected 	$text_prefix	= 'COM_BEESTOWORKFLOW_USERS';
	
	public function getModel($name = 'User', $prefix = 'BeestoWorkflowModel', $config = array('ignore_request' => true)) {
		$model = parent::getModel($name, $prefix, $config);
		return $model;
	}
	
	
	/*
	 * Method to add new user after the user was choosed
	 * 
	 * name: 		donew
	 * @param
	 * @return
	 */
	public function donew () {
		
		$user = JRequest::getVar('user', '' , '','int');
		$type = JRequest::getVar('type', '' , '','int');
		
		if (empty($user))  {
			$this->setRedirect( 'index.php?option=com_beestoworkflow&view=users', JText::_('COM_BEESTOWORKFLOW_USERS_NO_USER_SELECTED'), 'warning' );
		} else {
			$model = $this->getModel();
			$model->donew ( $user,$type );
			$this->setRedirect( 'index.php?option=com_beestoworkflow&view=users', JText::_('COM_BEESTOWORKFLOW_USERS_ADDED'), 'message' );
		}
	
	}
	

	
}

?>
