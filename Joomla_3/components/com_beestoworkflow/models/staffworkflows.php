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


class BeestoWorkflowModelStaffWorkflows extends JModelList
{
	
	public function __construct($config = array()) {
		
		parent::__construct($config);
	}
	
	
	
	protected function populateState($ordering = null, $direction = null) { 
		
		// Initialise variables.
		$app = JFactory::getApplication('administrator');
		
		// Load the filter state.
		$state = $app->getUserStateFromRequest($this->context.'.filter.search', 'filter_search', '', 'string');
		$this->setState('filter.search', $state);
		
		// Load category filter
		$state = $app->getUserStateFromRequest($this->context.'.filter.category', 'filter_category', '', 'string');
		$this->setState('filter.category', $state);
		
		// Load the parameters.
		$params = JComponentHelper::getParams('com_beestoworkflow');
		$this->setState('params', $params);

		// List state information.
		parent::populateState('a.id', 'desc');
	}
	
	protected function getStoreId($id = '') {
		
		// Compile the store id.
		$id	.= ':'.$this->getState('filter.search');
		$id	.= ':'.$this->getState('filter.category');

		return parent::getStoreId($id);
	}
	
	

	protected function getListQuery() {
		
		// Create a new query object.
		$db		= $this->getDbo();
		$query	= $db->getQuery(true);

		// Select the required fields from the table.
		$query->select(
			$this->getState(
				'list.select','a.id,a.title'
			)
		);
		$query->from(' #__beestowf_project_workflow AS a ');
		
		// Filter by search in title
		$search = $this->getState('filter.search');
		if (!empty($search))
		{
			if (stripos($search, 'id:') === 0) {
				$query->where('a.id = '.(int) substr($search, 3));
			}
			else
			{
				$search = $db->Quote('%'.$db->escape(strtolower($search), true).'%');
				$query->where( 'LOWER(a.title) LIKE '.$search );
			}
		}
		
		
		// Filter by category
		$status = $this->getState('filter.category');
		if($status) {
			if ($status == 'none') {
				$query->where(' ( a.owner = 0 OR a.owner = '. JFactory::getUser()->get('id') . ' )' );
			} else {
				$query->where(' a.owner = ' . $status );
			}
		} else {
			if ($status === 0) {
				$query->where(' a.owner = 0 ' );
			} else {
				$query->where(' ( a.owner = 0 OR a.owner = '. JFactory::getUser()->get('id') . ' )' );
			}
		}
		
		$query->where('a.published = 1');
		
		// Add the list ordering clause.
		$orderCol = $this->getState('list.ordering', 'a.id');
		//$query->order($db->getEscaped($orderCol).' '.$db->getEscaped($this->getState('list.direction', 'DESC')));

		return $query;
	}


	public function getCategories () {
		
		$user = JFactory::getUser()->get('id');
		
		$result[0] 		= JText::_('COM_BEESTOWORKFLOW_WORKFLOWS_PUBLIC_WORKFLOWS');
		$result[$user] 	= JText::_('COM_BEESTOWORKFLOW_WORKFLOWS_PRIVATE_WORKFLOWS');
		
		return $result;
		
	}
	
	
}
?>
