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

$action = JRoute::_('index.php?option=com_k2store&view=geozone');
JHtml::_('behavior.formvalidation');
JHtml::_('behavior.keepalive');
JHtml::_('behavior.tooltip');
?>

<script>
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
						jQuery('#zoneContainer').html(response);
						if (response.error != 1) {
							jQuery('#zoneContainer').html(response.success);
						} else {
							jQuery('#zoneError').html(response.errorMessage);
						}
					}
				});

		return false;
	}
function deleteGeoZoneRule(gzr_id,delete_btn){
	var data = {geozonerule_id : gzr_id};
	var delete_url = "<?php echo JURI::base();?>index.php?option=com_k2store&view=geozone&task=geozone.deleteGeoZoneRule";
	jQuery.post(  delete_url, data, function(response) {
			if(response.error!=1){
				jQuery(delete_btn).parent().parent().fadeOut();
				}
			else{
				jQuery('#geozoneruleError').html(response.errorMessage);
				jQuery('.error').fadeIn();
				}
		},'json');

	}

function addGeoZoneRule(geozone_id, country_id, zone_id) {
		var data = {
			jform : {
				geozone_id : geozone_id,
				country_id : country_id,
				zone_id : zone_id
			}
		};

		var country = jQuery("#jformcountry_id").children("option").filter(":selected").text() ;
		var zone = jQuery("#jform_zone_id").children("option").filter(":selected").text() ;
		jQuery.ajax({
					type : "POST",
					url : "<?php echo JURI::base();?>index.php?option=com_k2store&view=geozone&task=geozone.addGeoZoneRule",
					data : data,
					dataType: "json",
					success : function(response) {

						if (response.error != 1) {
							var gzr_id= response.geozonerule_id;
							var q="'";
							var links = '<a rel="{handler:'+q+'iframe'+q+',size:{x: window.innerWidth-450, y: window.innerHeight-150}}" '
										+' href="index.php?option=com_k2store&view=geozone&task=geozone.setrule&id='+
										gzr_id+'&tmpl=component" 	class="modal"><input type="button" value="<?php echo JText::_('K2STORE_EDIT'); ?>" class=" btn btn-primary"></a> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'
										+'<input onclick="deleteGeoZoneRule('+
										gzr_id+',this);" class="btn btn-danger" type="button" value="<?php echo JText::_('K2STORE_DELETE'); ?>">';
							//alert(links);
							var result='<tr><td></td><td id="country_'+gzr_id+'">'+country+'</td><td id="zone_'+gzr_id+'">'+zone+'</td><td>'+links+'</td></tr>';
							jQuery('#tableBody').append(result);
							// intialize squeeze box again for edit button to work
							SqueezeBox.initialize({});
							SqueezeBox.assign($$('a.modal'), {
							parse: 'rel'
							});
						} else {
							jQuery('#geozoneruleError').html(response.errorMessage);
							jQuery('.error').fadeIn();
						}
					}
				});

		return false;
	}


	window.addEvent('domready', function() {
		var zone_id=0;

		if(document.id('jformcountry_id')) {
			getAjaxZone('jform[zone_id]','jform_zone_id', document.id('jformcountry_id').value, zone_id);

			document.id('jformcountry_id').addEvents({
			change:function() {
				getAjaxZone('jform[zone_id]','jform_zone_id', document.id('jformcountry_id').value, zone_id);
			},
			load:function() {
				getAjaxZone('jform[zone_id]','jform_zone_id', document.id('jformcountry_id').value, zone_id);
			}
			});

			document.id('CreateGeoZoneRule').addEvents({
			click:function() {
				addGeoZoneRule(document.id('jform_geozone_id').value, document.id('jformcountry_id').value,document.id('jform_zone_id').value);
			}
			});
		}
	});


</script>

<div class="k2store">
<form action="<?php echo $action; ?>" method="post" name="adminForm"
	id="adminForm" class="form-validate">

	<div id="geozone_edit">
		<fieldset class="fieldset">
			<legend>
				<?php echo JText::_('K2STORE_GEOZONE'); ?>
			</legend>
			<table>
				<tr>
					<td><?php echo $this->form->getLabel('geozone_name'); ?>
					</td>
					<td><?php echo $this->form->getInput('geozone_name'); ?> <?php echo $this->form->getInput('geozone_id'); ?>
					</td>
				</tr>
				<tr>
					<td><?php echo $this->form->getLabel('state'); ?>
					</td>
					<td><?php echo $this->form->getInput('state'); ?>
					</td>
				</tr>
				<tr><td colspan="2"><div class="k2storehelp alert alert-info"><?php echo JText::_('K2STORE_GEOZONE_HELP_TEXT'); ?></div></td></tr>
			</table>
			<?php if (!empty($this->item->geozone_id)){ ?>

			<fieldset>
				<legend>
					<?php echo JText::_('K2STORE_GEOZONE_COUNTRIES_AND_ZONES'); ?>
				</legend>
				<table>
					<tr>
						<td><?php echo $this->lists['country']; ?>
						</td>
						<td id="zoneContainer"></td>
						<td id="zoneError">
							<div class="error alert alert-danger" style="display: none;">
								<?php echo JText::_('K2STORE_ERROR'); ?>
								<i class="icon-cancel pull-right" style="align: right;"
									onclick="jQuery(this).parent().fadeOut();"> </i> <br />
								<hr />
								<div id="geozoneruleError"></div>
							</div>
						</td>
					</tr>

					<tr>
						<td></td>
						<td><input type="button" id="CreateGeoZoneRule"
							value="<?php echo JText::_('K2STORE_GEOZONE_ADD_COUNTRY_OR_ZONE'); ?>"
							class="btn btn-success" />
						</td>
					</tr>

				</table>

				<table class="adminlist table table-striped table-bordered">
					<thead>
						<tr>
							<th><?php echo JText::_('K2STORE_NUM'); ?>

							</td>
							<th><?php echo JText::_('K2STORE_GEOZONE_COUNTRY'); ?>

							</td>
							<th><?php echo JText::_('K2STORE_ZONE'); ?>

							</td>
							<th></th>
						</tr>
					</thead>
					<tbody id="tableBody">
						<?php
						$i=1;
					foreach($this->geozonerules as $grule) { ?>
						<tr>
							<td><?php echo $i++; ?>
							</td>
							<td id="country_<?php echo $grule->id; ?>" ><?php echo $grule->country; ?>
							</td>
							<td id="zone_<?php echo $grule->id; ?>"><?php
							if($grule->zone_id==0)
								echo JText::_('K2STORE_ALL_ZONES');
							else
								echo $grule->zone;
							?>
							</td>
							<td><a
								rel="{handler:'iframe',size:{x: window.innerWidth-450, y: window.innerHeight-150}}"
								href="index.php?option=com_k2store&view=geozone&task=geozone.setrule&id=<?php echo $grule->id;?>&tmpl=component"
								class="modal"><input type="button"
									value="<?php echo JText::_('K2STORE_EDIT'); ?>"
									class=" btn btn-primary"> </a> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <input
								onclick="deleteGeoZoneRule(<?php echo $grule->id;?>,this);"
								class="btn btn-danger" type="button"
								value="<?php echo JText::_('K2STORE_DELETE'); ?>">
							</td>
						</tr>

						<?php }//end for?>
					</tbody>


				</table>

			</fieldset>
			<?php }// end if geozoneid empty ?>
		</fieldset>
	</div>
	<input type="hidden" name="option" value="com_k2store"> <input
		type="hidden" name="geozone_id"
		value="<?php echo $this->item->geozone_id; ?>"> <input type="hidden"
		name="task" value="">
	<?php echo JHTML::_( 'form.token' ); ?>
</form>
</div>