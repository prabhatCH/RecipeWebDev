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

JHtml::_('behavior.tooltip');
$listOrder	= $this->state->get('list.ordering');
$listDirn	= $this->state->get('list.direction');
$priority	= BeestoWorkflowHelper::getPriority();
$status 	= array(0=>JText::_('COM_BEESTOWORKFLOW_TASKS_ACTIVE'),1=>JText::_('COM_BEESTOWORKFLOW_TASKS_COMPLETE'));
$class 		= array(0=>'verylow',1=>'low',2=>'medium',3=>'high',4=>'veryhigh');
?>

<form action="<?php echo JRoute::_('index.php?option=com_beestoworkflow&view=tasks'); ?>" method="post" name="adminForm" id="adminForm">
	
	<div id="filter-bar" class="btn-toolbar">
		
		<div class="filter-search btn-group">
			<input type="text" class="input-medium pull-left" name="filter_search" id="filter_search" value="<?php echo $this->state->get('filter.search'); ?>" title="" placeholder="<?php echo JText::_('JSEARCH_FILTER_LABEL'); ?>"/>
			<button type="submit" class="btn btn-default"><?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?></button>
			<button type="button" class="btn btn-default" onclick="document.id('filter_search').value='';this.form.submit();"><?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?></button>
		</div>
		
		<div class="filter-search btn-group">
			<select name="filter_priority" class="input-medium" onchange="this.form.submit()">
				<option value="none"> - <?php echo JText::_('COM_BEESTOWORKFLOW_TASKS_SELECT_PRIORITY');?> - </option>
				<?php echo JHtml::_('select.options', $priority  , 'value', 'text', $this->state->get('filter.priority'));?>
			</select>
		
			<select name="filter_project" class="input-medium" onchange="this.form.submit()">
				<option value=""> - <?php echo JText::_('COM_BEESTOWORKFLOW_TASKS_SELECT_PROJECT');?> - </option>
				<?php echo JHtml::_('select.options', $this->projects  , 'value', 'text', $this->state->get('filter.project'));?>
			</select>
			
			<select name="filter_status" class="input-medium" onchange="this.form.submit()">
				<option value=""> - <?php echo JText::_('COM_BEESTOWORKFLOW_TASKS_SELECT_STATUS');?> - </option>
				<?php echo JHtml::_('select.options', $status  , 'value', 'text', $this->state->get('filter.status'));?>
			</select>
		</div>
		
	</div>
	
	<div class="clearfix"> </div>
	
	<table class="table">
		<thead>
			<tr>
			
				<th>
					<input type="checkbox" name="toggle" value="" onclick="Joomla.checkAll(this)" />
				</th>
				
				<th>
					<?php echo JHtml::_('grid.sort', 'COM_BEESTOWORKFLOW_TASKS_TASK_NAME', 'a.name', $listDirn, $listOrder); ?>
				</th>
				
				<th>
					<?php echo JHtml::_('grid.sort', 'COM_BEESTOWORKFLOW_TASKS_TASK_PROJECT', 'a.project_id', $listDirn, $listOrder); ?>
				</th>
				
				<th>
					<?php echo JText::_('COM_BEESTOWORKFLOW_TASKS_TASK_START'); ?>
				</th>
				
				<th>
					<?php echo JText::_('COM_BEESTOWORKFLOW_TASKS_TASK_DUE'); ?>
				</th>	
				
				<th>
					<?php echo JHtml::_('grid.sort', 'COM_BEESTOWORKFLOW_TASKS_TASK_PRIORITY', 'a.priority', $listDirn, $listOrder); ?>
				</th>
				
				<th>
					<?php echo JHtml::_('grid.sort', 'COM_BEESTOWORKFLOW_TASKS_TASK_COMPLETED', 'a.completed', $listDirn, $listOrder); ?>
				</th>
				
				<th>
					<?php echo JHtml::_('grid.sort', 'COM_BEESTOWORKFLOW_ID', 'a.id', $listDirn, $listOrder); ?>
				</th>
				
			</tr>
		</thead>

		<tbody>
		<?php foreach ($this->items as $i => $item) {
		?>
			<tr class="row<?php echo $i % 2; ?>">

				<td>
					<?php echo JHtml::_('grid.id', $i, $item->id); ?>
				</td>
				
				<td>
					<a href="index.php?option=com_beestoworkflow&task=task.edit&id=<?php echo $item->id;?>"><?php echo $this->escape($item->name);?></a>
				</td>
				
				<td>
					<?php echo ($name = BeestoWorkflowHelper::getProject($item->project_id,'name')) ? $name : JText::_('COM_BEESTOWORKFLOW_PROJECTS_NO_PROJECT') ;?>
				</td>

				<td>
					<?php echo $item->start;?>
				</td>
				
				<td>
					<?php echo $item->due;?>
				</td>
				
				<td>
					<span class="<?php echo $class[$item->priority];?>"><?php echo $priority[$item->priority];?></span>
				</td>
				
				<td>
					<?php echo $item->completed == 'completed' ? JText::_('JYES') : JText::_('JNO');?>
				</td>
				
				<td>
					<?php echo $item->id; ?>
				</td>
				
			</tr>

		<?php } ?>
		</tbody>
				
		<tfoot>
			<tr>
				<td colspan="8">
					<?php echo $this->pagination->getListFooter(); ?>
				</td>
			</tr>
		</tfoot>
	</table>

		<input type="hidden" name="task" value="" />
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
		<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
		<?php echo JHtml::_('form.token'); ?>
</form>
