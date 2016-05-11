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

jimport('joomla.application.component.view');

class BeestoWorkflowViewStaffworkflow extends JViewLegacy
{
	protected $item;
	protected $form;
	protected $state;
	protected $steps;
	protected $ordering;

	public function display($tpl = null)
	{

		$this->state		= $this->get('State');
		$this->item			= $this->get('Item');
		$this->form			= $this->get('Form');
		$this->steps		= $this->item->id ? $this->get('Steps') : null;
		$this->ordering		= $this->item->id ? $this->get('Ordering') : null;
	
		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			$app = new JApplication ();
			$app->redirect(JRoute::_('index.php?option=com_beestoworkflow&view=staffworkflows',false), implode("\n", $errors), 'warning');
			return false;
		}
		
		$this->addToolbar();
		parent::display($tpl);
	}

	protected function addToolbar() {
		JHtml::stylesheet( 'components/com_beestoworkflow/assets/css/default.css' );
		$this->menu = BeestoWorkflowMenu::getMenu(0);
		
	}
	
}
?>
