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

class BeestoWorkflowViewWorkflow extends JViewLegacy
{
	protected $item;
	protected $form;
	protected $state;

	public function display($tpl = null)
	{
		$this->state	= $this->get('State');
		$this->item		= $this->get('Item');
		$this->form		= $this->get('Form');

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

		if ($isNew) {
			JToolBarHelper::title(   'beestoworkflow :: ' . JText::_( 'COM_BEESTOWORKFLOW_USUAL_ADD' ), 'workflows' );	
		} else {
			JToolBarHelper::title(   'beestoworkflow :: ' . JText::_( 'COM_BEESTOWORKFLOW_USUAL_EDIT' ), 'workflows' );	
		}
	

		// If not checked out, can save the item.
		if ($canDo->get('core.edit')||($canDo->get('core.create'))) {
			JToolBarHelper::apply('workflow.apply', 'JTOOLBAR_APPLY');
			JToolBarHelper::save('workflow.save', 'JTOOLBAR_SAVE');
		}

		if (empty($this->item->id))  {
			JToolBarHelper::cancel('workflow.cancel','JTOOLBAR_CANCEL');
		} else {
			JToolBarHelper::cancel('workflow.cancel', 'JTOOLBAR_CLOSE');
		}
	}
}
?>
