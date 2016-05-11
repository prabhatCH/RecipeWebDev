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

$return 	= base64_encode(JRoute::_('index.php?option=com_beestoworkflow&view=staffworkflows',false));
$canCreateWorkflow = BeestoWorkflowHelper::canDo('createWorkflows');
?>



<div id="beestoworfklow">

	<?php echo $this->menu;?>

	<form action="<?php echo JRoute::_('index.php?option=com_beestoworkflow&view=staffworkflows', false); ?>" method="post" name="adminForm" id="adminForm">

		<div class="form-actions">
			<?php if ($canCreateWorkflow) { ?>
			<input type="button" class="btn btn-default" value="+ <?php echo JText::_('COM_BEESTOWORKFLOW_WORKFLOWS_NEW_WORKFLOW');?>" onclick="javascript:Joomla.submitbutton('staffworkflow.add')" />
			<?php } ?>
			<a class="btn btn-default" href="javascript:Joomla.submitbutton('staffworkflows.delete');"><i class="icon icon-delete"></i> <?php echo JText::_('COM_BEESTOWORKFLOW_STAFFPROJECTS_DELETE_SELECTED');?></a>
		</div>

		<div class="row-fluid">
			<div class="span12 page-header">
				<h1><i class="icon icon-tag"></i>&nbsp;&nbsp;&nbsp;<?php echo JText::_('COM_BEESTOWORKFLOW_WORKFLOWS_TITLE');?></h1>
			</div>
		</div>

		<div class="row-fluid">
			<div class="span12 page-header">
				<div class="btn-group">
					<input type="text" class="input-medium pull-left" name="filter_search" id="filter_search" value="<?php echo $this->state->get('filter.search'); ?>" placeholder="<?php echo JText::_('JSEARCH_FILTER_LABEL'); ?>" />
					<button type="submit" class="btn btn-default"><?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?></button>
					<button type="button" class="btn btn-default" onclick="document.id('filter_search').value='';this.form.submit();"><?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?></button>
				</div>
				<div class="btn-group">
					<select name="filter_category" class="inputbox" onchange="this.form.submit()">
						<option value="none">- <?php echo JText::_('COM_BEESTOWORKFLOW_WORKFLOWS_SELECT_TYPE');?> -</option>
						<?php echo JHtml::_('select.options', $this->categories  , 'value', 'text', $this->state->get('filter.category'));?>
					</select>
				</div>
			</div>
		</div>
			
		<table class="table table-hover">
			<thead>
				<tr>
				
					<th>
						<input type="checkbox" name="toggle" value="" onclick="Joomla.checkAll(this)" />
					</th>
					
					<th>
						<?php echo JText::_('COM_BEESTOWORKFLOW_WORKFLOWS_NAME');?>
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
					<a href="index.php?option=com_beestoworkflow&task=staffworkflow.edit&id=<?php echo $item->id;?>"><?php echo $this->escape($item->title);?></a>
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
		<input type="hidden" name="return-url" value="<?php echo $return;?>" />
		<?php echo JHtml::_('form.token'); ?>	
	</form>
</div>
