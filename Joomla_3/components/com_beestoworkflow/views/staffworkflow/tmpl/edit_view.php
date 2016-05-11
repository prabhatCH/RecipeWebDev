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
$priority 	= BeestoWorkflowHelper::getPriority();
$return 	= base64_encode(JRoute::_('index.php?option=com_beestoworkflow&task=staffworkflow.edit&id='.(int) $this->item->id,false));

?>

<div id="beestoworfklow">

	<?php echo $this->menu;?>
	
	<form action="<?php echo JRoute::_('index.php?option=com_beestoworkflow&view=staffworkflow&layout=edit&id='.(int) $this->item->id); ?>" method="post" name="adminForm" id="adminForm" class="form-validate">
	
		<div class="form-actions">
			<input type="button" class="btn btn-danger" onclick="javascript:Joomla.submitbutton('staffworkflow.cancel');" value="<?php echo JText::_('COM_BEESTOWORKFLOW_WORKFLOWS_CANCEL');?>" />
		</div>
		
		<legend><?php echo JText::_('COM_BEESTOWORKFLOW_WORKFLOWS_VIEW_WORKFLOW');?></legend>

		<div class="row-fluid">
			<div class="span12">
				<h3><?php echo $this->item->title ;?></h3>
			</div>
		</div>
		
		<div class="row-fluid">
			<div class="span12">
				<dl class="dl-horizontal">	
					<dt><?php echo JText::_('COM_BEESTOWORKFLOW_WORKFLOWS_CREATOR'); ?></dt>
					<dd><?php echo $this->item->owner == 0 ? JText::_('COM_BEESTOWORKFLOW_WORKFLOWS_PUBLIC_WORKFLOWS') : JFactory::getUser($this->item->owner)->get('name'); ?></dd>
					
					
					<dt><?php echo JText::_('COM_BEESTOWORKFLOW_WORKFLOWS_TASK_TYPE'); ?></dt>
					<dd><?php echo $this->item->type == 0 ? JText::_('COM_BEESTOWORKFLOW_WORKFLOWS_DEPENDANT_TASKS') : JText::_('COM_BEESTOWORKFLOW_WORKFLOWS_INDEPENDANT_TASKS'); ?></dd>
				</dl>
			</div>
		</div>

		<legend><?php echo JText::_('COM_BEESTOWORKFLOW_WORKFLOWS_STEPS');?></legend> 

		<?php if ($this->item->owner == JFactory::getUser()->get('id')) {?>
		<div class="form-actions">
			<a class="btn btn-default" href="javascript:Joomla.submitbutton('staffstep.add');document.adminForm.task.value=null;">+ <?php echo JText::_('COM_BEESTOWORKFLOW_WORKFLOWS_STEPS_ADD');?></a>
		</div>
		<?php } ?>


		<table class="table table-hover">
			<thead>
				<tr>
					<th></th>
					<th><?php echo JText::_('COM_BEESTOWORKFLOW_WORKFLOWS_STEPS_TITLE');?></th>
					<th><?php echo JText::_('COM_BEESTOWORKFLOW_WORKFLOWS_STEPS_PRIORITY');?></th>
					<th><?php echo JText::_('COM_BEESTOWORKFLOW_WORKFLOWS_STEPS_ORDER');?></th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				<?php if( !empty( $this->steps ) )  { 
						foreach ($this->steps as $i=>$step) {
						$orderkey	= isset( $this->ordering->values ) ? array_search($step->order,$this->ordering->values) : 0; ?>
					<tr>
						<td>
							<?php echo JHtml::_('grid.id', $i, $step->id); ?>
						</td>
						<td><a href="<?php echo JRoute::_('index.php?option=com_beestoworkflow&task=staffstep.edit&id=' . $step->id,false);?>"><?php echo $this->escape($step->title);?></a></td>
						<td><?php echo $priority[$step->priority];?></td>
						<td>
								<?php if ($this->item->owner == JFactory::getUser()->get('id')) {?>
								<span>
									<?php if ($orderkey<$this->ordering->max) { ?>
										<a href="javascript:void(0);" onclick="return listItemTask('cb<?php echo $i;?>','staffworkflow.orderdown')"><?php echo JHTML::_('image', 'components/com_beestoworkflow/assets/images/icon-14-down.png', 'up' );?></a>
									<?php } ?>	
								</span>
								<span>
									<?php if ($orderkey>$this->ordering->min) { ?>
										<a href="javascript:void(0);" onclick="return listItemTask('cb<?php echo $i;?>','staffworkflow.orderup')"><?php echo JHTML::_('image', 'components/com_beestoworkflow/assets/images/icon-14-up.png', 'down' );?></a>
									<?php } ?>	
								</span>
								<?php } ?>
								<input type="text" name="order[]" size="5" value="<?php echo ($orderkey+1);?>" class="text-area-order" disabled />	
						</td>
						<td>
						<?php if ($this->item->owner == JFactory::getUser()->get('id')) {?>
							 <a href="javascript:void(0);" onclick="return listItemTask('cb<?php echo $i;?>','staffstep.delete');"><?php echo JHTML::_('image', 'components/com_beestoworkflow/assets/images/icon-16-delete.png', 'delete' );?></a>
						<?php } ?>
						</td>
					</tr>
				<?php } } ?>
			</tbody>
		</table>

	

		<input type="hidden" name="return-url" value="<?php echo $return ; ?>" />
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="workflow_id" value="<?php echo $this->item->id;?>" />
		<?php echo JHtml::_('form.token'); ?>
	</form>
</div>	
