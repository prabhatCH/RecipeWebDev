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

class BeestoWorkflowControllerTasks extends JControllerAdmin {
	
	protected	$option 		= 'com_beestoworkflow';
	protected 	$text_prefix	= 'COM_BEESTOWORKFLOW_TASKS';
	protected 	$context 		= 'com_beestoworkflow.tasks';
	
	public function display($cachable = false, $urlparams = false)
	{
		// Get the document object.
		$document	= JFactory::getDocument();
		$form 		= JRequest::getVar('jform');
		
		$vName		= 'tasks';
		$vFormat	= $form['exported'] == 'xls' ? 'xls' : 'pdf';

		// Get and render the view.
		if ($view = $this->getView($vName, $vFormat)) {
			// Get the model for the view.
			$model = $this->getModel($vName);

			// Load the filter state.
			$app = JFactory::getApplication();

			//$model->setState('list.limit', 0);
			//$model->setState('list.start', 0);

			$model->setState('basename', $form['basename']);
			$model->setState('exported', $form['exported']);

			// Push the model into the view (as default).
			$view->setModel($model, true);

			// Push document object into the view.
			$view->assignRef('document', $document);

			$view->display();
		}
	}

	
}

?>
