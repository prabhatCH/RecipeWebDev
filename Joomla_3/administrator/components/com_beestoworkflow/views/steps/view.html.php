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

//jimport( 'joomla.application.component.view' );	
 
class BeestoWorkflowViewSteps extends JViewLegacy
{
	protected $items;
	protected $pagination;
	protected $state;
	protected $ordering;

	function display($tpl = null) {
		
		$this->items		= $this->get('Items');
		$this->pagination	= $this->get('Pagination');
		$this->state		= $this->get('State');
		$this->ordering		= $this->get('Ordering');
		
		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}
		
		$this->addToolbar();
		parent::display($tpl);
		
	}
	
	public function addToolbar() {	
		
		JHtml::stylesheet( 'administrator/components/com_beestoworkflow/assets/css/default.css' );

		$canDo	= BeestoWorkflowHelper::getActions();
		
		JToolBarHelper::title(   'beestoworkflow :: ' . JText::_( 'COM_BEESTOWORKFLOW_WORKFLOWS_STEPS_TASKS' ), 'workflows' );	
		
		JToolBarHelper::custom('cpanel', 'default.png', 'default.png', 'COM_BEESTOWORKFLOW_CPANEL' , false);
		JToolBarHelper::divider();
		
		if ($canDo->get('core.create')) {
			JToolBarHelper::addNew('step.add', 'JTOOLBAR_NEW');
		}
		if ($canDo->get('core.edit')) {
			JToolBarHelper::editList('step.edit','JTOOLBAR_EDIT');
		}
		
		if ($canDo->get('core.delete')) {
			JToolBarHelper::custom('steps.delete', 'delete.png', 'delete.png', 'JTOOLBAR_DELETE' , false);
		}
		
		JToolBarHelper::divider();
		
		if ($canDo->get('core.admin')) {
			JToolBarHelper::preferences('com_beestoworkflow');
			JToolBarHelper::divider();
		}		
	}
	
}
?>
