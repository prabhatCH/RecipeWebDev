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

jimport('joomla.application.component.modelform');


class BeestoWorkflowModelDTasks extends JModelForm
{
	private $_context = 'com_beestoworkflow.dtasks';
	
	protected function populateState()
	{
		$app = JFactory::getApplication('administrator');
		$basename = $app->getUserStateFromRequest($this->_context.'.filter.basename', 'filter_basename', '__CATNAME__', 'string');
		$this->setState('basename', $basename);

		$compressed = JRequest::getInt(JApplication::getHash($this->_context.'.compressed'), 1, 'cookie');
		$this->setState('compressed', $compressed);
	}


	public function getForm($data = array(), $loadData = true)
	{
		// Get the form.
		$form = $this->loadForm('com_beestoworkflow.dtasks', 'dtasks', array('control' => 'jform', 'load_data' => $loadData));
		if (empty($form)) {
			return false;
		}

		return $form;
	}

	protected function loadFormData()
	{
		return array(
			'basename'		=> $this->getState('basename'),
			'exported'		=> $this->getState('exported')
		);
	}
}
