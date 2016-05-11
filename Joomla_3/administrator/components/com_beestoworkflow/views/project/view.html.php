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

//jimport('joomla.application.component.view');

class BeestoWorkflowViewProject extends JViewLegacy
{
	protected $item;
	protected $form;
	protected $state;
	protected $tasks;
	protected $attachments;
	protected $comments;

	public function display($tpl = null)
	{
		$this->state		= $this->get('State');
		$this->item			= $this->get('Item');
		$this->form			= $this->get('Form');
		$this->tasks		= $this->get('Tasks');
		$this->attachments	= $this->get('Attachments');
		$this->comments		= $this->get('Comments');
		
		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}

		$this->addToolbar();
		parent::display($tpl);
	}

	protected function addToolbar() {
		
		JHtml::stylesheet( 'administrator/components/com_beestoworkflow/assets/css/default.css' );
		JRequest::setVar('hidemainmenu', true);
		
		$canDo		= BeestoWorkflowHelper::getActions();
		$isNew		= ($this->item->id == 0);

		JToolBarHelper::title(   'beestoworkflow :: ' . JText::_( 'COM_BEESTOWORKFLOW_PROJECTS_VIEW_DETAILS' ), 'projects' );	
	
		if (empty($this->item->id))  {
			JToolBarHelper::cancel('project.cancel','JTOOLBAR_CANCEL');
		} else {
			JToolBarHelper::cancel('project.cancel', 'JTOOLBAR_CLOSE');
		}
	}
}
?>
