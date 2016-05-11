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

jimport('joomla.application.component.modeladmin');


class BeestoWorkflowModelUser extends JModelAdmin
{

	protected $text_prefix = 'COM_BEESTOWORKFLOW_USERS';


	protected function canDelete($record)
	{
		$user = JFactory::getUser();

		if (!empty($record->catid)) {
			return $user->authorise('core.delete', 'com_beestoworkflow.user');
		}
		else {
			return parent::canDelete($record);
		}
	}

	protected function canEditState($record)
	{
		$user = JFactory::getUser();

		if (!empty($record->catid)) {
			return $user->authorise('core.edit.state', 'com_beestoworkflow.user');
		}
		else {
			return parent::canEditState($record);
		}
	}


	public function getTable($type = 'BWUser', $prefix = 'BeestoWorkflowTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}


	public function getForm($data = array(), $loadData = true)
	{
		// Get the form.
		$form = $this->loadForm('com_beestoworkflow.user', 'user', array('control' => 'jform', 'load_data' => $loadData));
		if (empty($form)) {
			return false;
		}
		
		return $form;
	}

	protected function loadFormData()
	{
		// Check the session for previously entered form data.
		$data = JFactory::getApplication()->getUserState('com_beestoworkflow.edit.user.data', array());

		if (empty($data)) {
			$data = $this->getItem();
		}

		return $data;
	}

	
	
	public function donew( $id,$type ) {
		
		$query = ' INSERT INTO #__beestowf_users (id,type,department,email,phone,company_name,company_address,company_city,company_zip,company_country,company_email,company_phone,company_fax,company_website,published, params) ' .
				 ' VALUES ('.$id.','.$type.',"","","","","","","","","","","","",0,"") ' ;
		
		$this->_db->setQuery($query);
		$this->_db->Query();
		
	}
	
	
	protected function prepareTable( $table ) {
		
		$params = JRequest::getVar('params', array(), '', 'array');

		$registry = new JRegistry();
		$registry->set('params',$params);
		$table->params = (string) $registry;
		
	}
	

}
?>
