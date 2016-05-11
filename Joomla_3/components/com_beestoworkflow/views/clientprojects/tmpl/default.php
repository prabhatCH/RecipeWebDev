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

$listOrder	= $this->state->get('list.ordering');
$listDirn	= $this->state->get('list.direction');
$return 	= base64_encode(JRoute::_('index.php?option=com_beestoworkflow&view=staffprojects',false));
$priority 	= BeestoWorkflowHelper::getPriority();
$state		= BeestoWorkflowHelper::getProjectStatuses();

?>

<div id="beestoworfklow">

	<?php echo $this->menu;?>
	
	<form action="<?php echo JRoute::_('index.php?option=com_beestoworkflow&view=clientprojects', false); ?>" method="post" name="adminForm" id="adminForm">

		<div class="form-actions">
			<input type="button" class="btn" value="<?php echo JText::_('COM_BEESTOWORKFLOW_CLIENT_NEW_PROJECT');?>" onclick="javascript:Joomla.submitbutton('clientproject.add')" /></li>
		</div>
		
		<legend><?php echo JText::_('COM_BEESTOWORKFLOW_CLIENT_SUMMARY_ALL_PROJECTS');?></legend>
		
		<div class="row-fluid">
			<div class="span12 page-header">
				
				<div class="filter-search btn-group">
					<input type="text" class="input-medium pull-left" name="filter_search" id="filter_search" value="<?php echo $this->state->get('filter.search'); ?>" placeholder="<?php echo JText::_('JSEARCH_FILTER_LABEL'); ?>" />
					<button type="submit" class="btn btn-default"><?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?></button>
					<button type="button" class="btn btn-default" onclick="document.id('filter_search').value='';this.form.submit();"><?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?></button>
				</div>
			
				<div class="filter-status btn-group">
					<select name="filter_status" class="input-medium" onchange="this.form.submit()">
						<option value="3">- <?php echo JText::_('COM_BEESTOWORKFLOW_CLIENT_SELECT_STATUS');?> -</option>
						<?php echo JHtml::_('select.options', $state  , 'value', 'text', $this->state->get('filter.status'));?>
					</select>
				</div>
			
			</div>
		</div>
	

		<table class="table table-hover">
			<thead>
				<tr>
				
					<th>
						<input type="checkbox" name="toggle" value="" onclick="checkAll(this)" />
					</th>
					
					<th>
						<?php echo JText::_('COM_BEESTOWORKFLOW_CLIENT_PROJECT_NAME');?>
					</th>
					
					<th>
						<?php echo JText::_('COM_BEESTOWORKFLOW_CLIENT_PROJECT_START'); ?>
					</th>
					
					<th>
						<?php echo JText::_('COM_BEESTOWORKFLOW_CLIENT_PROJECT_DUE'); ?>
					</th>
					
					<th>
						<?php echo JText::_('COM_BEESTOWORKFLOW_CLIENT_PROJECT_STATUS'); ?>
					</th>
					
					<th>
						<?php echo JText::_('COM_BEESTOWORKFLOW_STAFFPROJECTS_MANAGER'); ?>
					</th>	
					
					<th>
						<?php echo JText::_('COM_BEESTOWORKFLOW_ID');?>
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
					<a href="index.php?option=com_beestoworkflow&task=clientproject.edit&id=<?php echo $item->id;?>"><?php echo $this->escape($item->name);?></a>
				</td>
				
				<td>
					<?php echo $this->escape($item->start);?>
				</td>
				
				<td>
					<?php echo $this->escape($item->due);?>
				</td>
				
				<td>
					<span class="status<?php echo $item->status;?>"><?php echo $state[$item->status];?></span>
				</td>
				
				<td>
					<?php echo $item->manager ? $this->escape($item->manager) : JText::_('COM_BEESTOWORKFLOW_CLIENT_PROJECT_NOT_ASSIGNED');?>
				</td>
				
				<td>
					<?php echo $item->id; ?>
				</td>
				
			</tr>

			<?php } ?>
			</tbody>
						
			<tfoot>
				<tr>
					<td colspan="7">
						<?php echo $this->pagination->getListFooter(); ?>
					</td>
				</tr>
			</tfoot>
		</table>


		<input type="hidden" name="task" value="" />
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
		<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
		<input type="hidden" name="return" value="<?php echo $return;?>" />
		<?php echo JHtml::_('form.token'); ?>
		
	</form>
</div>
