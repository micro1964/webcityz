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
$baseURL = JURI::root();
$document = JFactory::getDocument();

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

function saveGeoZoneRule(country_id, zone_id) {
		var data = {
			jform : {
				geozonerule_id : <?php echo $this->item->id;?>,
				geozone_id : <?php echo $this->item->geozone_id;?>,
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
							window.parent.jQuery('#country_'+gzr_id).html(country);
							window.parent.jQuery('#zone_'+gzr_id).html(zone);

							// intialize squeeze box again for edit button to work
							window.parent.SqueezeBox.initialize({});
							window.parent.SqueezeBox.assign($$('a.modal'), {
							parse: 'rel'
							});
							window.parent.SqueezeBox.close();

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
		zone_id=<?php echo $this->item->zone_id; ?>;
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
				saveGeoZoneRule( document.id('jformcountry_id').value,document.id('jform_zone_id').value);
			}
			});
		}
	});


</script>


<div class="k2store" id="geozone_edit">

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
					value="<?php echo JText::_('JTOOLBAR_SAVE'); ?>" class="btn btn-success" />
				</td>
				<td><input type="button" onclick="window.parent.SqueezeBox.close();"
					value="<?php echo JText::_('JTOOLBAR_CANCEL'); ?>" class="btn btn-success" /></td>
			</tr>

		</table>
	</fieldset>
</div>
