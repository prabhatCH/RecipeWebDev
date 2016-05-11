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

class BeestoWorkflowControllerStaffAlerts extends JControllerAdmin {
	
	protected	$option 		= 'com_beestoworkflow';
	protected 	$text_prefix	= 'COM_BEESTOWORKFLOW_ALERTS';
	
	public function getModel($name = 'StaffAlerts', $prefix = 'BeestoWorkflowModel', $config = array('ignore_request' => true)) {
		$model = parent::getModel($name, $prefix, $config);
		return $model;
	}

	
}

?>
