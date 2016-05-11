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

class BeestoWorkflowViewClienttask extends JViewLegacy
{
	protected $item;
	protected $form;
	protected $state;
	protected $attachments;
	protected $comments;
	protected $assignees;
	protected $project;
	protected $status;
	protected $params;
	protected $completedby;


	public function display($tpl = null)
	{
		$this->state		= $this->get('State');
		$this->item			= $this->get('Item');
		$this->form			= $this->get('Form');
		$this->attachments	= $this->item->id ? $this->get('Attachments') : null;
		$this->comments		= $this->item->id ? $this->get('Comments') : null;
		$this->assignees	= $this->item->id ? $this->get('Assignees') : null;
		$this->project		= $this->get('Project');
		$this->status		= $this->get('Status');
		$this->params		= $this->get('Params');
		$this->completedby	= $this->item->id ? $this->get('WhoCompleted') : array();
		
		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			$app = new JApplication ();
			$app->redirect(JRoute::_('index.php?option=com_beestoworkflow&view=clienttasks',false), implode("\n", $errors), 'warning');
			return false;
		}
		
		$this->addToolbar();
		parent::display($tpl);
	}

	protected function addToolbar() {
		JHtml::script( 'components/com_beestoworkflow/assets/functions.js' );
		JHtml::stylesheet( 'components/com_beestoworkflow/assets/css/default.css' );
		$this->menu = BeestoWorkflowMenu::getMenu(1);
		
	}
	
}
?>
