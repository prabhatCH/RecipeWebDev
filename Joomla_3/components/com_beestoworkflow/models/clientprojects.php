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

jimport( 'joomla.application.component.modellist' );


class BeestoWorkflowModelClientProjects extends JModelList
{
	protected $userID;
	
	public function __construct($config = array()) {
		if (empty($config['filter_fields'])) {
			$config['filter_fields'] = array(
				'a.id',
				'a.name',
				'a.start',
				'a.due',
				'b.name',
				'a.status',
			);
		}
		$this->userID = JFactory::getUser()->get('id');
		parent::__construct($config);
	}
	
	
	
	protected function populateState($ordering = null, $direction = null) { 
		
		// Initialise variables.
		$app = JFactory::getApplication('administrator');
		
		// Load the filter state.
		$state = $app->getUserStateFromRequest($this->context.'.filter.search', 'filter_search', '', 'string');
		$this->setState('filter.search', $state);
		
		// set to load active projects
		$status = $app->getUserStateFromRequest($this->context.'.filter.status', 'filter_status', '', 'int');
		$this->setState('filter.status', $status);
		
		// Load the parameters.
		$params = JComponentHelper::getParams('com_beestoworkflow');
		$this->setState('params', $params);

		// List state information.
		parent::populateState('a.id', 'desc');
	}
	
	protected function getStoreId($id = '') {
		
		// Compile the store id.
		$id	.= ':'.$this->getState('filter.search');
		$id	.= ':'.$this->getState('filter.status');

		return parent::getStoreId($id);
	}
	
	

	protected function getListQuery() {
		
		// Create a new query object.
		$db		= $this->getDbo();
		$query	= $db->getQuery(true);
		$canViewAll = BeestoWorkflowHelper::canDo('view_allprojects');

		// Select the required fields from the table.
		$query->select(
			$this->getState(
				'list.select','a.id,a.name,a.start,a.due,b.name AS manager, a.status'
			)
		);
		$query->from(' #__beestowf_projects AS a ');
		$query->join('LEFT','#__users AS b ON a.manager = b.id ');
		
		// Filter by access
		if(!$canViewAll) { 
			$query->where(' a.client = '. $this->userID);
		}
		$query->where(' a.client_view = 1 ');
		
		// Filter by search in title
		$search = $this->getState('filter.search');
		if (!empty($search)) {
			$search = $db->Quote('%'.$db->escape(strtolower($search), true).'%');
			$query->where( 'LOWER(a.name) LIKE '.$search );
		}
		
		// Filter by status
		$status = $this->getState('filter.status');
		if ((!empty($status) || $status === 0) && $status != 3){
			$query->where('a.status = '. $status);
		}
	
		// Add the list ordering clause.
		$orderCol = $this->getState('list.ordering', 'a.id');
		$query->order($db->escape($orderCol).' '.$db->escape($this->getState('list.direction', 'DESC')));

		return $query;
	}


	


	
}
?>
