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
$return = base64_encode(JRoute::_('index.php?option=com_beestoworkflow',false));

?>

<div id="beestoworfklow">
	
	<?php echo $this->menu;?>

	<form action="<?php echo JRoute::_('index.php?option=com_beestoworkflow', false);?>" method="post" name="adminForm" id="adminForm">
	
		<div class="row-fluid">
			<div class="span12 page-header">
				<h3><span class="badge badge-info"><?php echo BeestoWorkflowHelper::getTime ('Y, M d') ;?></span> <?php echo JText::_('COM_BEESTOWORKFLOW_CLIENT_MENU_SUMMARY');?></h3>
			</div>
		</div>
		
		<div class="row-fluid">
			<div class="span12">
				<h4><i class="icon icon-briefcase"></i>&nbsp;&nbsp;&nbsp;<?php echo JText::_('COM_BEESTOWORKFLOW_CLIENT_SUMMARY_ALL_PROJECTS');?></h4>
			</div>
		</div>
		
		
		<div class="form-actions">
			<p><a class="btn btn-default" href="javascript:Joomla.submitbutton('clientproject.add');"><?php echo JText::_('COM_BEESTOWORKFLOW_CLIENT_NEW_PROJECT');?></a>&nbsp;&nbsp;&nbsp;  
				<?php echo JText::_('COM_BEESTOWORKFLOW_CLIENT_VIEW');?>: 
				<a class="" href="<?php echo JRoute::_('index.php?option=com_beestoworkflow&view=clientprojects',false) ;?>"><?php echo JText::_('COM_BEESTOWORKFLOW_CLIENT_PROJECTS');?></a>
			</p>
		</div>

		<input type="hidden" name="task" value="" />
		<input type="hidden" name="boxchecked" value="0" />
		<?php echo JHtml::_('form.token'); ?>
	</form>
</div>
