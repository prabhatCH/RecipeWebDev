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


class BeestoWorkflowModelSteps extends JModelList
{
	protected	$option 		= 'COM_BEESTOWORKFLOW_STEPS';
	
	
	public function __construct($config = array()) {
		if (empty($config['filter_fields'])) {
			$config['filter_fields'] = array(
				'a.id',
				'a.title',
				'a.order',
				'a.priority',
			);
		}

		parent::__construct($config);
	}
	
	
	
	protected function populateState($ordering = null, $direction = null) { 
		
		// Initialise variables.
		$app = JFactory::getApplication('administrator');
		
		// Load the filter state.
		$state = $app->getUserStateFromRequest('workflow_id','',0);
		$this->setState('filter.workflow', $state);
		
		$state = $app->getUserStateFromRequest($this->context.'.filter.search', 'filter_search', '', 'string');
		$this->setState('filter.search', $state);
		
		// Load the parameters.
		$params = JComponentHelper::getParams('com_beestoworkflow');
		$this->setState('params', $params);

		// List state information.
		parent::populateState('a.order', 'asc');
	}
	
	protected function getStoreId($id = '') {
		
		// Compile the store id.
		$id	.= ':'.$this->getState('filter.workflow');
		$id	.= ':'.$this->getState('filter.search');

		return parent::getStoreId($id);
	}
	
	

	protected function getListQuery() {
		
		// Create a new query object.
		$db			= $this->getDbo();
		$query		= $db->getQuery(true);
		$workflow	= $this->getState('filter.workflow');

		// Select the required fields from the table.
		$query->select(
			$this->getState(
				'list.select','a.id,a.title,a.order,a.priority'
			)
		);
		$query->from(' #__beestowf_project_workflow_steps AS a');
		
		$query->where('a.project_workflow = ' . $workflow );
	
		// Filter by search in title
		$search = $this->getState('filter.search');
		if (!empty($search))
		{
			if (stripos($search, 'id:') === 0) {
				$query->where('a.id = '.(int) substr($search, 3));
			}
			else
			{
				$search = $db->Quote('%'.$db->escape($search, true).'%');
				$query->where('( a.title LIKE '.$search.' )');
			}
		}

		
		// Add the list ordering clause.
		$orderCol = $this->getState('list.ordering', 'a.order');
		$query->order($db->escape($orderCol).' '.$db->escape($this->getState('list.direction', 'ASC')));

		return $query;
	}
	

	public function getOrdering() {
		
		$workflow	= $this->getState('filter.workflow');
		
		$query = ' SELECT a.order FROM #__beestowf_project_workflow_steps AS a WHERE a.project_workflow = ' .$workflow. ' ORDER BY a.order ASC ';
		$this->_db->setQuery($query);
		$ordering = $this->_db->loadColumn();
		return $ordering;
		
	}
	
	
}
?>
