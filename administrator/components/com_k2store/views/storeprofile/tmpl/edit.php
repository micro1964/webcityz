<?php
/*------------------------------------------------------------------------
 # com_k2store - K2 Store
# ------------------------------------------------------------------------
# author    Sasi varna kumar - Weblogicx India http://www.weblogicxindia.com
# copyright Copyright (C) 2012 Weblogicxindia.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://k2store.org
# Technical Support:  Forum - http://k2store.org/forum/index.html
-------------------------------------------------------------------------*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die( 'Restricted access' );

$action = JRoute::_('index.php?option=com_k2store&view=storeprofile');
JHtml::_('behavior.keepalive');
?>

  <script type="text/javascript">
  <!--
 function getAjaxZone(field_name, field_id, country_value, default_zid) {
		var data = {
			jform : {
				country_id : country_value,
				zone_id : default_zid,
				field_name : field_name,
				field_id : field_id
			}
		};

		jQuery.ajax({
					type : "POST",
					url : "<?php echo JURI::base();?>index.php?option=com_k2store&view=geozone&task=geozone.getZone",
					data : data,
					success : function(response) {
						K2Store('#zoneContainer').html(response);
						if (response.error != 1) {
							K2Store('#zoneContainer').html(response.success);
						} else {
							K2Store('#zoneError').html(response.errorMessage);
						}
					}
				});

		return false;
	}

	jQuery(document).ready(function(){
		var zone_id;
		<?php if(isset($this->item->zone_id)) { ?>
		zone_id = <?php echo $bzone_id=($this->item->zone_id)?$this->item->zone_id:0; ?>;
		<?php } else { ?>
		zone_id=0;
		<?php } ?>

		if(jQuery('#jformcountry_id')) {
			getAjaxZone('jform[zone_id]','jform_zone_id', jQuery('#jformcountry_id').val(), zone_id);

			jQuery("#jformcountry_id").bind('change load', function(){
				getAjaxZone('jform[zone_id]','jform_zone_id', jQuery('#jformcountry_id').val(), zone_id);
			});
		}

	});


-->
</script>
<div class="k2store">
<form action="<?php echo $action; ?>" method="post" name="adminForm" id="adminForm">
<?php //print_r($this->item); ?>
	<div id="zone_edit">
		<fieldset class="fieldset">
			<legend>
				<?php echo JText::_('K2STORE_STOREPROFILE'); ?>
			</legend>
			<table>
				<tr>
					<td><?php echo $this->form->getLabel('store_name'); ?>
					</td>
					<td><?php echo $this->form->getInput('store_name'); ?>
					</td>
				</tr>


				<tr>
					<td><?php echo $this->form->getLabel('store_desc'); ?>
					</td>
					<td><?php echo $this->form->getInput('store_desc'); ?>
					</td>
				</tr>
				<tr>
					<td><?php echo $this->form->getLabel('country_id'); ?>
					</td>
					<td><?php echo $this->form->getInput('country_id'); ?>
					</td>
					<td><small class="alert alert-info"><?php echo JText::_('K2STORE_STOREPROFILE_COUNTRY_HELP_TEXT');?></small></td>
				</tr>

				<tr>
					<td><?php echo $this->form->getLabel('zone_id'); ?><br />
					</td>
					<td id="zoneContainer"><?php // echo $this->form->getInput('zone_id'); ?>
					</td>
					<td><small class="alert alert-info"><?php echo JText::_('K2STORE_STOREPROFILE_ZONE_HELP_TEXT');?></small></td>
				</tr>

				<tr>
					<td><?php echo $this->form->getLabel('state'); ?>
					</td>
					<td><?php echo $this->form->getInput('state'); ?>
					</td>
				</tr>

			</table>
		</fieldset>
	</div>
	<input type="hidden" name="option" value="com_k2store"> <input
		type="hidden" name="store_id"
		value="<?php echo $this->item->store_id; ?>"> <input type="hidden"
		name="task" value="">
	<?php echo JHTML::_( 'form.token' ); ?>
</form>
</div>