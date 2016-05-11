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

class BeestoWorkflowControllerStaffProfileUser extends JControllerForm {
	
	protected	$option 		= 'com_beestoworkflow';
	protected 	$text_prefix	= 'COM_BEESTOWORKFLOW_STAFFPROFILE';
	
	
	public function cancel() {

		// Check for request forgeries
		JRequest::checkToken('request') or jexit(JText::_('JINVALID_TOKEN'));
		
		$app 			= JFactory::getApplication();
		$profileReturn 	= $app->getUserState('com_beestoworkflow.session.return');
		
		if(empty($profileReturn)) {
			$this->setRedirect (JRoute::_('index.php?option=com_beestoworkflow',false));
		} 
		
		$profileReturn 	= base64_decode($profileReturn);
		$this->setRedirect ( $profileReturn );

	}
	
	
	
	public function edit () {
	
		$return = JRequest::getVar ('return',null,'','base64');
		$app = JFactory::getApplication();
		$app->setUserState('com_beestoworkflow.session.return', $return);
		parent::edit();
		
	}
	
	
	
	
	
	
	
}

?>
