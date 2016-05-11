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

class BeestoWorkflowControllerStaffProfile extends JControllerForm {
	
	protected	$option 		= 'com_beestoworkflow';
	protected 	$text_prefix	= 'COM_BEESTOWORKFLOW_STAFFPROFILE';
	
	
	public function save() {
		
		// Check for request forgeries.
		JRequest::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		// Initialise variables.
		$model	= $this->getModel('StaffProfile', 'BeestoWorkflowModel');

		// Get the user data.
		$data = JRequest::getVar('jform', array(), 'post', 'array');
		
		// Validate the posted data.
		$form = $model->getForm();
		if (!$form) {
			JError::raiseError(500, $model->getError());
			return false;
		}
		
		$return = $model->save($data);

		// Check for errors.
		if ($return === false) {

			$this->setMessage(JText::sprintf('COM_BEESTOWORKFLOW_PROFILE_SAVE_FAILED', $model->getError()), 'warning');
			$this->setRedirect(JRoute::_('index.php?option=com_beestoworkflow&view=staffprofile&layout=edit',false));
			return false;
		}

		
		$this->setRedirect ( JRoute::_('index.php?option=com_beestoworkflow&view=staffprofile&layout=edit',false) );
		
	}
	
}

?>
