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
 
class BeestoWorkflowViewProjects extends JViewLegacy
{
	protected $items;
	protected $pagination;
	protected $state;


	function display($tpl = null) {
		
		$this->items		= $this->get('Items');
		$this->pagination	= $this->get('Pagination');
		$this->state		= $this->get('State');
		
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
		
		JToolBarHelper::title(   'beestoworkflow :: ' . JText::_( 'COM_BEESTOWORKFLOW_PROJECTS' ), 'briefcase' );	
		
		$bar = JToolBar::getInstance('toolbar');
		$bar->appendButton('Popup', 'reports-export', 'JTOOLBAR_EXPORT', 'index.php?option=com_beestoworkflow&view=dprojects&tmpl=component', 600, 300);
		
		if ($canDo->get('core.delete')) {
			JToolBarHelper::custom('project.delete', 'delete.png', 'delete.png', 'JTOOLBAR_DELETE' , true);
		}
		
		JToolBarHelper::divider();
		
		if ($canDo->get('core.admin')) {
			JToolBarHelper::preferences('com_beestoworkflow');
			JToolBarHelper::divider();
		}		
	}
	
}
?>
