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

class BeestoWorkflowViewStaffproject extends JViewLegacy
{
	protected $item;
	protected $form;
	protected $state;
	protected $tasks;
	protected $attachments;
	protected $comments;
	protected $params;

	public function display($tpl = null)
	{
		$this->get('Authorisation');
		$this->state		= $this->get('State');
		$this->item			= $this->get('Item');
		$this->form			= $this->get('Form');
		$this->tasks		= $this->item->id ? $this->get('Tasks') : null;
		$this->attachments	= $this->item->id ? $this->get('Attachments') : null;
		$this->comments		= $this->item->id ? $this->get('Comments') : null;
		$this->params		= $this->get('Params');
		
		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			$app = new JApplication ();
			$app->redirect(JRoute::_('index.php?option=com_beestoworkflow&view=staffprojects',false), implode("\n", $errors), 'warning');
			return false;
		}
		
		$this->addToolbar();
		parent::display($tpl);
	}

	protected function addToolbar() {
		JHtml::stylesheet( 'components/com_beestoworkflow/assets/css/default.css' );
		JHtml::script( 'components/com_beestoworkflow/assets/functions.js' );
		$this->menu = BeestoWorkflowMenu::getMenu(0);
		
	}
	
}
?>
