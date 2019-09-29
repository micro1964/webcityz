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

$action = JRoute::_('index.php?option=com_k2store&view=taxprofile');
JHtml::_('behavior.formvalidation');
JHtml::_('behavior.keepalive');
JHtml::_('behavior.tooltip');
?>

<script>
function deleteTaxRule(gzr_id,delete_btn){
	var data = {taxrule_id : gzr_id};
	var delete_url = "<?php echo JURI::base();?>index.php?option=com_k2store&view=taxprofile&task=taxprofile.deleteTaxRule";
	jQuery.post(  delete_url, data, function(response) {
			if(response.error!=1){
				jQuery(delete_btn).parent().parent().fadeOut();
				}
			else{
				jQuery('#taxruleError').html(response.errorMessage);
				jQuery('.error').fadeIn();
				}
		},'json');

	}

function addTaxRule(taxprofile_id, taxrate_id, address) {
	if(taxrate_id=='' || address==''){
		jQuery('#taxruleError').html('invalid selection');
		jQuery('.error').fadeIn();
		return false;
		}

		var data = {
			jform : {
				taxprofile_id : taxprofile_id,
				taxrate_id : taxrate_id,
				address : address
			}
		};

		var taxrate = jQuery("#jformtaxrate_id").children("option").filter(":selected").text() ;
		var zone = jQuery("#jform_address").children("option").filter(":selected").text() ;
		jQuery.ajax({
					type : "POST",
					url : "<?php echo JURI::base();?>index.php?option=com_k2store&view=taxprofile&task=taxprofile.addTaxRule",
					data : data,
					dataType: "json",
					success : function(response) {

						if (response.error != 1) {
							var gzr_id= response.taxrule_id;
							var q="'";
							var links = '<a rel="{handler:'+q+'iframe'+q+',size:{x: window.innerWidth-450, y: window.innerHeight-150}}" '
										+' href="index.php?option=com_k2store&view=taxprofile&task=taxprofile.settaxrule&id='+
										gzr_id+'&tmpl=component" 	class="modal"><input type="button" value="<?php echo JText::_('K2STORE_EDIT'); ?>" class=" btn btn-primary"></a> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'
										+'<input onclick="deleteTaxRule('+
										gzr_id+',this);" class="btn btn-danger" type="button" value="<?php echo JText::_('K2STORE_DELETE'); ?>">';
							//alert(links);
							var result='<tr><td></td><td id="taxrate_'+gzr_id+'">'+taxrate+'</td><td id="address_'+gzr_id+'">'+address+'</td><td>'+links+'</td></tr>';
							jQuery('#tableBody').append(result);
							// intialize squeeze box again for edit button to work
							SqueezeBox.initialize({});
							SqueezeBox.assign($$('a.modal'), {
							parse: 'rel'
							});
						} else {
							jQuery('#taxruleError').html(response.errorMessage);
							jQuery('.error').fadeIn();
						}
					}
				});

		return false;
	}


			var address=0;
			jQuery(document).on('click', '#CreateTaxRule', function() {
					addTaxRule(jQuery('#jform_taxprofile_id').val(), jQuery('#jformtaxrate_id').val(),jQuery('#jformaddress').val());
			});


</script>
<div class="k2store">
<div class="k2store-help alert alert-info">
	<?php echo JText::_('K2STORE_TAXPROFILES_HELP_TEXT');?>
</div>

<form action="<?php echo $action; ?>" method="post" name="adminForm"
	id="adminForm" class="form-validate">

	<div id="taxprofile_edit">
		<fieldset class="fieldset">
			<legend>
				<?php echo JText::_('K2STORE_TAXPROFILE'); ?>
			</legend>
			<table>
				<tr>
					<td><?php echo $this->form->getLabel('taxprofile_name'); ?>
					</td>
					<td><?php echo $this->form->getInput('taxprofile_name'); ?>
					</td>
				</tr>
				<tr>
					<td><?php echo $this->form->getLabel('state'); ?>
					</td>
					<td><?php echo $this->form->getInput('state'); ?>
					<?php echo $this->form->getInput('taxprofile_id'); ?>
					</td>
				</tr>

			</table>
			<?php if (!empty($this->item->taxprofile_id)){ ?>

			<fieldset>
				<legend>
					<?php echo JText::_('K2STORE_TAXPROFILE_TAXRATES'); ?>
					<small><?php echo JText::_('K2STORE_TAXPROFILE_TAXRATE_MAP_HELP'); ?></small>
				</legend>
				<table>
					<tr>
						<td><?php echo $this->lists['taxrate']; ?>
						</td>
						<td><?php echo $this->lists['address']; ?>
						<td valign="top"><input type="button" id="CreateTaxRule"
							value="<?php echo JText::_('K2STORE_TAXPROFILE_ADD_TAXRATE'); ?>"
							class="btn btn-success" />
						</td>
					</tr>

					<tfoot>
						<tr>
						<td id="zoneError" colspan="3">
							<div class="error alert alert-danger" style="display: none;">
								<?php echo JText::_('K2STORE_ERROR'); ?>
								<i class="icon-cancel pull-right" style="align: right;"
									onclick="jQuery(this).parent().fadeOut();"> </i> <br />
								<hr />
								<div id="taxruleError"></div>
							</div>
						</td>
						</tr>
					</tfoot>

				</table>

				<table class="adminlist table table-striped ">
					<thead>
						<tr>
							<th><?php echo JText::_('K2STORE_NUM'); ?>

							</td>
							<th><?php echo JText::_('K2STORE_TAXPROFILE_TAXRATE'); ?>

							</td>
							<th><?php echo JText::_('K2STORE_TAXPROFILE_ADDRESS'); ?>

							</td>
							<th></th>
						</tr>
					</thead>
					<tbody id="tableBody">
						<?php
						$i=1;
					foreach($this->taxrules as $trule) { ?>
						<tr>
							<td><?php echo $i++; ?>
							</td>
							<td id="taxrate_<?php echo $trule->id; ?>" ><?php echo $trule->taxrate_name; ?>&nbsp;(<?php echo floatval($trule->tax_percent); ?>%)
							</td>
							<td id="address_<?php echo $trule->id; ?>"><?php
								echo $trule->address;
							?>
							</td>
							<td><a
								rel="{handler:'iframe',size:{x: window.innerWidth-450, y: window.innerHeight-150}}"
								href="index.php?option=com_k2store&view=taxprofile&task=taxprofile.settaxrule&id=<?php echo $trule->id;?>&tmpl=component"
								class="modal"><input type="button"
									value="<?php echo JText::_('K2STORE_EDIT'); ?>"
									class=" btn btn-primary"> </a> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <input
								onclick="deleteTaxRule(<?php echo $trule->id;?>,this);"
								class="btn btn-danger" type="button"
								value="<?php echo JText::_('K2STORE_DELETE'); ?>">
							</td>
						</tr>

						<?php }//end for?>
					</tbody>


				</table>

			</fieldset>
			<?php }// end if taxprofileid empty ?>
		</fieldset>
	</div>
	<input type="hidden" name="option" value="com_k2store"> <input
		type="hidden" name="taxprofile_id"
		value="<?php echo $this->item->taxprofile_id; ?>"> <input type="hidden"
		name="task" value="">
	<?php echo JHTML::_( 'form.token' ); ?>
</form>
</div>