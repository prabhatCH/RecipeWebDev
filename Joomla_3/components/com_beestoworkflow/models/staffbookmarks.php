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


class BeestoWorkflowModelStaffBookmarks extends JModelList
{
	
	public function __construct($config = array()) {
		
		parent::__construct($config);
	}
	
	
	
	protected function populateState($ordering = null, $direction = null) { 
		
		// Initialise variables.
		$app = JFactory::getApplication('administrator');
		
		// Load the parameters.
		$params = JComponentHelper::getParams('com_beestoworkflow');
		$this->setState('params', $params);

		// List state information.
		parent::populateState('a.id', 'desc');
	}
	
	protected function getStoreId($id = '') {
		
		// Compile the store id.
		return parent::getStoreId($id);
	}
	
	

	protected function getListQuery() {
		
		// Create a new query object.
		$db		= $this->getDbo();
		$query	= $db->getQuery(true);

		// Select the required fields from the table.
		$query->select(
			$this->getState(
				'list.select','a.id,a.location'
			)
		);
		$query->from(' #__beestowf_bookmarks AS a ');
		
		// filter by owner
		$query->where('a.owner = '. JFactory::getUser()->get('id'));
	
		// Add the list ordering clause.
		$orderCol = $this->getState('list.ordering', 'a.id');
		$query->order('a.id DESC');

		return $query;
	}


	public function add ( $url ) {
		
		$table = JTable::getInstance('Bookmark','BeestoWorkflowTable');
		$table->location = $url;
		$table->owner = JFactory::getUser()->get('id');
		$table->store();
		
	}
	
	
	public function delete ( $urls ) {
		
		$owner = JFactory::getUser()->get('id');
		if ($urls) {
			$query = ' DELETE FROM #__beestowf_bookmarks WHERE id IN ('. implode(',',$urls) . ') AND owner = ' . $owner;
			$this->_db->setQuery($query);
			$this->_db->Query();
		}	
	}
	
}
?>
