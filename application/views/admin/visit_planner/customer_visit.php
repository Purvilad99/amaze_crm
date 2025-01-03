<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <?php
            echo form_open($this->uri->uri_string(), ['id' => 'customer_visit-form', 'class' => '_bookingunfix_transaction_form booking-unfix-form','enctype' => 'multipart/form-data']);
            ?>
            <div class="col-md-12">
                <h4 class="tw-mt-0 tw-font-semibold tw-text-lg tw-text-neutral-700 tw-flex tw-items-center tw-space-x-2">
                    <span>
                    <?= !empty($customer_visit) ? _l('edit_new_customer_visit') : _l('create_new_customer_visit'); ?>
                    </span>
                </h4>

                <div class="panel_s invoice accounting-template">

                    <div class="additional"></div>

                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-6">
                                <input type="hidden" name="visit_planner_id" id="visit_planner_id" value="<?= $visit_planner_id ?>">

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
<!--                                            --><?php //$value = $customer_visit['subject']; ?>
                                            <?php $value = (!empty($customer_visit['subject'])) ? $customer_visit['subject'] : $visit_planner['subject'] ?>
                                            <label for="time" class="control-label"><?php echo _l('proposal_subject'); ?><span class="text-danger"> *</span></label>
                                            <input class="form-control form-check-input" type="text" id="subject" name="subject" value="<?= $value ?>">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="type" class="control-label"><?php echo _l('visit_planner_type'); ?></label><span class="text-danger"> *</span>
                                            <select id="type" name="type" data-live-search="true" data-width="100%" class="selectpicker projects ajax-search">
                                                <option value=""></option>
                                                <?php if(!empty($customer_visit['type'])) { ?>
                                                    <option value="<?php echo _l('visit_planner_planned_visit'); ?>" <?= ($visit_planner['type'] == _l('visit_planner_planned_visit')) ? 'selected' : '' ?>><?php echo _l('visit_planner_planned_visit'); ?></option>
                                                    <option value="<?php echo _l('visit_planner_unplanned_visit'); ?>" <?= ($visit_planner['type'] == _l('visit_planner_unplanned_visit')) ? 'selected' : '' ?>><?php echo _l('visit_planner_unplanned_visit'); ?></option>
                                                <?php }else{ ?>
                                                    <option value="<?php echo _l('visit_planner_planned_visit'); ?>" <?= ($visit_planner['type'] == _l('visit_planner_planned_visit')) ? 'selected' : '' ?>><?php echo _l('visit_planner_planned_visit'); ?></option>
                                                <option value="<?php echo _l('visit_planner_unplanned_visit'); ?>" <?= ($visit_planner['type'] == _l('visit_planner_unplanned_visit')) ? 'selected' : '' ?>><?php echo _l('visit_planner_unplanned_visit'); ?></option>
                                                <?php } ?>
                                            </select>
                                            <label id="type-error" class="error hide" for="type">This field is required.</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <?php $value = (!empty($visit_planner['datetime'])) ? $visit_planner['datetime'] : $customer_visit['datetime'] ?>
                                            <label for="time" class="control-label"><?php echo _l('visit_planner_date'); ?><span class="text-danger"> *</span></label>
                                            <input class="form-control form-check-input" type="datetime-local" id="visit_planner_datetime" name="datetime" value="<?= $value ?>">
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="type" class="control-label"><?php echo _l('proposal_related'); ?></label><span class="text-danger"> *</span>
                                    <select id="rel_type" name="rel_type" data-live-search="true" data-width="100%" class="selectpicker ajax-search">
                                        <option value=""></option>
                                        <?php if(!empty($customer_visit['rel_type'])) { ?>
                                            <option value="<?php echo _l('visit_planner_lead'); ?>" <?= ($customer_visit['rel_type'] == _l('visit_planner_lead')) ? 'selected' : '' ?>><?php echo _l('visit_planner_lead'); ?></option>
                                            <option value="<?php echo _l('visit_planner_customer'); ?>" <?= ($customer_visit['rel_type'] == _l('visit_planner_customer')) ? 'selected' : '' ?>><?php echo _l('visit_planner_customer'); ?></option>
                                        <?php }else{ ?>
                                            <option value="<?php echo _l('visit_planner_lead'); ?>" <?= ($visit_planner['rel_type'] == _l('visit_planner_lead')) ? 'selected' : '' ?>><?php echo _l('visit_planner_lead'); ?></option>
                                            <option value="<?php echo _l('visit_planner_customer'); ?>" <?= ($visit_planner['rel_type'] == _l('visit_planner_customer')) ? 'selected' : '' ?>><?php echo _l('visit_planner_customer'); ?></option>
                                        <?php } ?>
                                    </select>
                                    <label id="type-error" class="error hide" for="type">This field is required.</label>
                                </div>

                                <div class="row customer_field" style="display:none;">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="clientid" class="control-label"><?php echo _l('visit_planner_customer'); ?><span class="text-danger"> *</span></label>
                                            <?php $customers = $this->db->get('tblclients')->result_array(); ?>
                                            <select id="clientid" name="clientid" data-live-search="true" data-width="100%" class="selectpicker ajax-search">
                                                <option value=""></option>
                                                <?php foreach ($customers as $customer) { ?>
                                                    <?php if(!empty($customer_visit['clientid'])) { ?>
                                                        <option value="<?php echo $customer['userid']; ?>" <?= ($customer_visit['clientid'] == $customer['userid']) ? 'selected' : '' ?>><?php echo $customer['company']; ?></option>
                                                    <?php }else{ ?>
                                                        <option value="<?php echo $customer['userid']; ?>" <?= ($visit_planner['clientid'] == $customer['userid']) ? 'selected' : '' ?>><?php echo $customer['company']; ?></option>
                                                    <?php } ?>
                                                <?php } ?>
                                            </select>
                                            <label id="client-error" class="error hide" for="location">This field is required.</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6 show_contacts">
                                        <div class="form-group">
                                            <label for="contacts" class="control-label"><?php echo _l('visit_planner_contacts'); ?><span class="text-danger"> *</span></label>
                                            <select id="contacts" name="contacts[]" multiple data-live-search="true" data-width="100%" class="selectpicker ajax-search">
                                                <option value=""></option>
                                            </select>
                                            <label id="client-error" class="error hide" for="location">This field is required.</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group lead_field" style="display:none;">
                                    <label for="clientid" class="control-label"><?php echo _l('visit_planner_lead'); ?><span class="text-danger"> *</span></label>
                                    <?php $leads = $this->db->get('tblleads')->result_array(); ?>
                                    <select id="leadid" name="leadid" data-live-search="true" data-width="100%" class="selectpicker ajax-search">
                                        <option value=""></option>
                                        <?php foreach ($leads as $lead) { ?>
                                            <?php if(!empty($customer_visit['clientid'])) { ?>
                                                <option value="<?php echo $lead['id']; ?>" <?= ($customer_visit['leadid'] == $lead['id']) ? 'selected' : '' ?>><?php echo $lead['name']; ?></option>
                                            <?php }else{ ?>
                                                <option value="<?php echo $lead['id']; ?>" <?= ($visit_planner['leadid'] == $lead['id']) ? 'selected' : '' ?>><?php echo $lead['name']; ?></option>
                                            <?php } ?>
                                        <?php } ?>
                                    </select>
                                    <label id="client-error" class="error hide" for="location">This field is required.</label>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="clientid" class="control-label"><?php echo _l('visit_planner_product_explain'); ?></label>
                                            <select id="product_explain" name="product_explain" data-live-search="true" data-width="100%" class="selectpicker projects ajax-search">
                                                <option value=""></option>
                                                <option value="<?php echo _l('visit_planner_pre_mix_boric'); ?>" <?= ($customer_visit['product_explain'] == _l('visit_planner_pre_mix_boric')) ? 'selected' : '' ?>><?php echo _l('visit_planner_pre_mix_boric'); ?></option>
                                                <option value="<?php echo _l('visit_planner_pre_mix_boron'); ?>" <?= ($customer_visit['product_explain'] == _l('visit_planner_pre_mix_boron')) ? 'selected' : '' ?>><?php echo _l('visit_planner_pre_mix_boron'); ?></option>
                                                <option value="<?php echo _l('visit_planner_nali_top'); ?>" <?= ($customer_visit['product_explain'] == _l('visit_planner_nali_top')) ? 'selected' : '' ?>><?php echo _l('visit_planner_nali_top'); ?></option>
                                                <option value="<?php echo _l('visit_planner_neutral_ramming_mass'); ?>" <?= ($customer_visit['product_explain'] == _l('visit_planner_neutral_ramming_mass')) ? 'selected' : '' ?>><?php echo _l('visit_planner_neutral_ramming_mass'); ?></option>
                                                <option value="<?php echo _l('visit_planner_mexheatA'); ?>" <?= ($customer_visit['product_explain'] == _l('visit_planner_mexheatA')) ? 'selected' : '' ?>><?php echo _l('visit_planner_mexheatA'); ?></option>
                                                <option value="<?php echo _l('visit_planner_mexheatK'); ?>" <?= ($customer_visit['product_explain'] == _l('visit_planner_mexheatK')) ? 'selected' : '' ?>><?php echo _l('visit_planner_mexheatK'); ?></option>
                                                <option value="<?php echo _l('visit_planner_mexcote95'); ?>" <?= ($customer_visit['product_explain'] == _l('visit_planner_mexcote95')) ? 'selected' : '' ?>><?php echo _l('visit_planner_mexcote95'); ?></option>
                                                <option value="<?php echo _l('visit_planner_castable90s'); ?>" <?= ($customer_visit['product_explain'] == _l('visit_planner_castable90s')) ? 'selected' : '' ?>><?php echo _l('visit_planner_castable90s'); ?></option>
                                                <option value="<?php echo _l('visit_planner_castable60s'); ?>" <?= ($customer_visit['product_explain'] == _l('visit_planner_castable60s')) ? 'selected' : '' ?>><?php echo _l('visit_planner_castable60s'); ?></option>
                                                <option value="<?php echo _l('visit_planner_other'); ?>" <?= ($customer_visit['product_explain'] == _l('visit_planner_other')) ? 'selected' : '' ?>><?php echo _l('visit_planner_other'); ?></option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="clientid" class="control-label"><?php echo _l('visit_planner_apply_opportunity'); ?></label>
                                            <select id="opportunity" name="opportunity" data-live-search="true" data-width="100%" class="selectpicker projects ajax-search">
                                                <option value=""></option>
                                                <option value="<?php echo _l('visit_planner_pre_mix_boric'); ?>" <?= ($customer_visit['opportunity'] == _l('visit_planner_pre_mix_boric')) ? 'selected' : '' ?>><?php echo _l('visit_planner_pre_mix_boric'); ?></option>
                                                <option value="<?php echo _l('visit_planner_pre_mix_boron'); ?>" <?= ($customer_visit['opportunity'] == _l('visit_planner_pre_mix_boron')) ? 'selected' : '' ?>><?php echo _l('visit_planner_pre_mix_boron'); ?></option>
                                                <option value="<?php echo _l('visit_planner_nali_top'); ?>" <?= ($customer_visit['opportunity'] == _l('visit_planner_nali_top')) ? 'selected' : '' ?>><?php echo _l('visit_planner_nali_top'); ?></option>
                                                <option value="<?php echo _l('visit_planner_neutral_ramming_mass'); ?>" <?= ($customer_visit['opportunity'] == _l('visit_planner_neutral_ramming_mass')) ? 'selected' : '' ?>><?php echo _l('visit_planner_neutral_ramming_mass'); ?></option>
                                                <option value="<?php echo _l('visit_planner_mexheatA'); ?>" <?= ($customer_visit['opportunity'] == _l('visit_planner_mexheatA')) ? 'selected' : '' ?>><?php echo _l('visit_planner_mexheatA'); ?></option>
                                                <option value="<?php echo _l('visit_planner_mexheatK'); ?>" <?= ($customer_visit['opportunity'] == _l('visit_planner_mexheatK')) ? 'selected' : '' ?>><?php echo _l('visit_planner_mexheatK'); ?></option>
                                                <option value="<?php echo _l('visit_planner_mexcote95'); ?>" <?= ($customer_visit['opportunity'] == _l('visit_planner_mexcote95')) ? 'selected' : '' ?>><?php echo _l('visit_planner_mexcote95'); ?></option>
                                                <option value="<?php echo _l('visit_planner_castable90s'); ?>" <?= ($customer_visit['opportunity'] == _l('visit_planner_castable90s')) ? 'selected' : '' ?>><?php echo _l('visit_planner_castable90s'); ?></option>
                                                <option value="<?php echo _l('visit_planner_castable60s'); ?>" <?= ($customer_visit['opportunity'] == _l('visit_planner_castable60s')) ? 'selected' : '' ?>><?php echo _l('visit_planner_castable60s'); ?></option>
                                                <option value="<?php echo _l('visit_planner_other'); ?>" <?= ($customer_visit['opportunity'] == _l('visit_planner_other')) ? 'selected' : '' ?>><?php echo _l('visit_planner_other'); ?></option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 show_p_other" <?= (!empty($customer_visit['p_other'])) ? 'style="display:block;"' : 'style="display:none;"' ?>>
                                        <div class="form-group">
                                            <input class="form-control" type="text" name="p_other" id="p_other" value="<?= $customer_visit['p_other'] ?>" placeholder="<?php echo _l('visit_planner_other'); ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-6 show_o_other" <?= (!empty($customer_visit['o_other'])) ? 'style="display:block;"' : 'style="display:none;"' ?>>
                                        <div class="form-group">
                                            <input class="form-control" type="text" name="o_other" id="o_other" value="<?= $customer_visit['o_other'] ?>" placeholder="<?php echo _l('visit_planner_other'); ?>">
                                        </div>
                                    </div>
                                </div>


                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <?php $value = (!empty($customer_visit['nxt_followup_date'])) ? $customer_visit['nxt_followup_date'] : date('d-m-Y h:m A'); ?>
                                            <label for="time" class="control-label"><?php echo _l('visit_planner_nxt_followup_date'); ?></label>
                                            <input class="form-control form-check-input" type="datetime-local" id="visit_planner_nxt_followup_datetime" name="nxt_followup_date" value="<?= $value ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group no-mbot">
                                            <label for="tags" class="control-label"><i class="fa fa-tag" aria-hidden="true"></i>
                                                <?php echo _l('tags'); ?></label>
                                            <input type="text" class="tagsinput" id="tags" name="tags"
                                                   value="<?= $customer_visit['tags'] ?>"
                                                   data-role="tagsinput">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="clientid" class="control-label"><?php echo _l('visit_planner_file'); ?></label>
                                            <div class="row">
                                                <?php
                                                $i = 0;
                                                if(!empty($images)){ ?>
                                                    </br>
                                                    <?php foreach ($images as $img) {  ?>
                                                        <div class="col-lg-3" style="margin-bottom:10px;position: relative;">
                                                            <div class="mb-3">
                                                                <?php $fileextension = explode('.', $img['file']);
                                                                if(end($fileextension) == 'png' || end($fileextension) == 'jpg' || end($fileextension) == 'jpeg'){ ?>
                                                                    <div class="image-area">
                                                                        <a href="<?= base_url('assets/images/customer_visit/'.$customer_visit['id'].'/'.$img['file'] ) ?>" target="_blank">
                                                                            <img src="<?= base_url('assets/images/customer_visit/'.$customer_visit['id'].'/'.$img['file'] ) ?>" style="margin-right: 10px;">
                                                                        </a>
                                                                        <a class="remove-image remove-old-img-button" data-img="<?= $img['file'] ?>" style="display: inline;">&#215;</a>
                                                                    </div>
                                                                <?php }elseif (end($fileextension) == 'pdf' || end($fileextension) == 'xlsx'){ ?>
                                                                    <div class="document-area">
                                                                        <a class="btn btn-sm btn-success" href="<?= base_url('assets/images/customer_visit/'.$visit_planner['id'].'/'.$img['file'] ) ?>" target="_blank"><i class="mdi mdi-eye"></i>&nbsp;&nbsp;<?= $img['file'] ?></a>
                                                                        <a class="remove-image remove-old-img-button" data-img="<?= $img['file'] ?>" style="display: inline;">&#215;</a>
                                                                    </div>
                                                                <?php } ?>

                                                            </div>
                                                        </div>
                                                    <?php } ?>
                                                <?php }
                                                ?>
                                            </div>
                                            <div class="old_img_data"></div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <input class="form-control form-check-input" type="file" id="file" name="file[]" accept=".png,.jpg,.jpeg,.pdf,.xlsx" multiple>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr class="hr-panel-separator" />

                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="show_inquiry_form" class="control-label"><?php echo _l('visit_planner_employee_inquiry_form'); ?> :&nbsp;&nbsp;</label>
                                    <?php if(!empty($customer_visit['show_inquiry_form'])){ ?>
                                        <input class="form-check-input" type="radio" id="show_inquiry_form" name="show_inquiry_form" <?= ($customer_visit['show_inquiry_form'] == 'yes') ? 'checked' : '' ?> value="yes">
                                        <label class="form-check-label" for="show_inquiry_form">Yes</label>&nbsp;&nbsp;&nbsp;
                                        <input class="form-check-input" type="radio" id="show_inquiry_form" name="show_inquiry_form" <?= ($customer_visit['show_inquiry_form'] == 'no') ? 'checked' : '' ?> value="no">
                                        <label class="form-check-label" for="show_inquiry_form">No</label>
                                    <?php }else{ ?>
                                        <input class="form-check-input" type="radio" id="show_inquiry_form" name="show_inquiry_form" value="yes">
                                        <label class="form-check-label" for="show_inquiry_form">Yes</label>&nbsp;&nbsp;&nbsp;
                                        <input class="form-check-input" type="radio" id="show_inquiry_form" name="show_inquiry_form" checked value="no">
                                        <label class="form-check-label" for="show_inquiry_form">No</label>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive visit_planner_enquiry_form s_table" <?= ($customer_visit['show_inquiry_form'] == 'yes') ? 'style="display:block;"' : 'style="display:none;"' ?>>
                            <table class="table invoice-items-table items table-main-invoice-edit has-calculations no-mtop enquiry_form" style="border : 1px solid ;">
                                <tr>
                                    <td><label for="end_product" class="control-label"><?php echo _l('visit_planner_end_product'); ?></label></td>
                                    <td colspan="2">
                                        <select id="end_product" name="end_product" data-live-search="true" data-width="100%" class="selectpicker ajax-search">
                                            <option value=""></option>
                                            <?php foreach ($items as $itm) { ?>
                                                <option value="<?php echo $itm['id']; ?>" <?= ($customer_visit['end_product'] == $itm['id']) ? 'selected' : '' ?>><?php echo $itm['description']; ?></option>
                                            <?php } ?>
                                        </select>
                                    </td>
                                    <td colspan="4" rowspan="7"  class="text-center">
                                        <img class="img img-responsive" src="<?php echo base_url('assets/images/diagram4.png') ?>" style="margin-left: 100px;">
                                    </td>
                                </tr>
                                <tr>
                                    <td><label for="total_no_of_panels" class="control-label"><?php echo _l('visit_planner_total_no_of_panels'); ?></label></td>
                                    <td>
                                        <select id="total_no_of_panels" name="total_no_of_panels" data-live-search="true" data-width="100%" class="selectpicker ajax-search">
                                            <option value=""></option>
                                            <option value="1" <?= ($customer_visit['total_no_of_panels'] == 1) ? 'selected' : '' ?>>1</option>
                                            <option value="2" <?= ($customer_visit['total_no_of_panels'] == 2) ? 'selected' : '' ?>>2</option>
                                            <option value="3" <?= ($customer_visit['total_no_of_panels'] == 3) ? 'selected' : '' ?>>3</option>
                                            <option value="4" <?= ($customer_visit['total_no_of_panels'] == 4) ? 'selected' : '' ?>>4</option>
                                            <option value="5" <?= ($customer_visit['total_no_of_panels'] == 5) ? 'selected' : '' ?>>5</option>
                                        </select>
                                        <!-- <input type="text" class="form-control" name="total_no_of_panels" id="total_no_of_panels" value="--><?//= $visit_planner['total_no_of_panels'] ?><!--" placeholder="--><?php //echo _l('visit_planner_total_no_of_panels'); ?><!--" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');">-->
                                    </td>
                                    <td class="text-center"><?php echo _l('visit_planner_no'); ?></td>
                                </tr>
                                <tr>
                                    <td><label for="month_prod" class="control-label"><?php echo _l('visit_planner_month_prod'); ?></label></td>
                                    <td><input type="text" class="form-control" name="month_prod" id="month_prod" value="<?= $customer_visit['month_prod'] ?>" placeholder="<?php echo _l('visit_planner_month_prod'); ?>" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');"></td>
                                    <td class="text-center"><?php echo _l('visit_planner_lmt'); ?></td>
                                </tr>
                                <tr>
                                    <td><label for="clientid" class="control-label"><?php echo _l('visit_planner_sintering_panel'); ?></label></td>
                                    <td colspan="2">
                                        <?php if(!empty($visit_planner['sintering_panel'])) { ?>
                                            <input class="form-check-input" type="radio" id="yes" name="sintering_panel" <?= ($customer_visit['sintering_panel'] == 'yes') ? 'checked' : '' ?> value="yes">
                                            <label class="form-check-label" for="yes">Yes</label>&nbsp;&nbsp;&nbsp;
                                            <input class="form-check-input" type="radio" id="no" name="sintering_panel" <?= ($customer_visit['sintering_panel'] == 'no') ? 'checked' : '' ?> value="no">
                                            <label class="form-check-label" for="no">No</label>
                                        <?php }else{ ?>
                                            <input class="form-check-input" type="radio" id="yes" name="sintering_panel" checked value="yes">
                                            <label class="form-check-label" for="yes">Yes</label>&nbsp;&nbsp;&nbsp;
                                            <input class="form-check-input" type="radio" id="no" name="sintering_panel" value="no">
                                            <label class="form-check-label" for="no">No</label>
                                        <?php } ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td><label for="kw_rating_sp" class="control-label"><?php echo _l('visit_planner_kw_rating_sp'); ?></label></td>
                                    <td><input type="text" class="form-control" name="kw_rating_sp" id="kw_rating_sp" value="<?= $customer_visit['kw_rating_sp'] ?>" placeholder="<?php echo _l('visit_planner_kw_rating_sp'); ?>" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');"></td>
                                    <td class="text-center"><?php echo _l('visit_planner_kw'); ?></td>
                                </tr>
                                <tr>
                                    <td><label for="frequency" class="control-label"><?php echo _l('visit_planner_frequency'); ?></label></td>
                                    <td><input type="text" class="form-control" name="frequency" id="frequency" value="<?= $customer_visit['frequency'] ?>" placeholder="<?php echo _l('visit_planner_frequency'); ?>" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');"></td>
                                    <td class="text-center"><?php echo _l('visit_planner_Hz'); ?></td>
                                </tr>
                                <tr>
                                    <td><label for="asbestos" class="control-label"><?php echo _l('visit_planner_asbestos'); ?></label></td>
                                    <td><input type="text" class="form-control" name="asbestos" id="asbestos" value="<?= $customer_visit['asbestos'] ?>" placeholder="<?php echo _l('visit_planner_asbestos'); ?>" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');"></td>
                                    <td class="text-center"><?php echo _l('visit_planner_after_coil_coat'); ?></td>
                                </tr>
                                <tr>
                                    <td><label for="maximum_tapping_temperature" class="control-label"><?php echo _l('visit_planner_maximum_tapping_temperature'); ?></label></td>
                                    <td><input type="text" class="form-control" name="maximum_tapping_temperature" id="maximum_tapping_temperature" value="<?= $customer_visit['maximum_tapping_temperature'] ?>" placeholder="<?php echo _l('visit_planner_maximum_tapping_temperature'); ?>" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');"></td>
                                    <td class="text-center"><?php echo _l('visit_planner_maximum_c'); ?></td>
                                    <td><label for="diagram_a" class="control-label"><?php echo _l('visit_planner_A'); ?></label></td>
                                    <td><input type="text" class="form-control" name="diagram_a" id="diagram_a" value="<?= $customer_visit['diagram_a'] ?>" placeholder="<?php echo _l('visit_planner_A'); ?>" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');"></td>
                                    <td><label for="diagram_e" class="control-label"><?php echo _l('visit_planner_E'); ?></label></td>
                                    <td><input type="text" class="form-control" name="diagram_e" id="diagram_e" value="<?= $customer_visit['diagram_e'] ?>" placeholder="<?php echo _l('visit_planner_E'); ?>" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');"></td>
                                </tr>
                                <tr>
                                    <td><label for="average_tapping_temperature" class="control-label"><?php echo _l('visit_planner_average_tapping_temperature'); ?></label></td>
                                    <td><input type="text" class="form-control" name="average_tapping_temperature" id="average_tapping_temperature" value="<?= $customer_visit['average_tapping_temperature'] ?>" placeholder="<?php echo _l('visit_planner_average_tapping_temperature'); ?>" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');"></td>
                                    <td class="text-center"><?php echo _l('visit_planner_maximum_c'); ?></td>
                                    <td><label for="diagram_b" class="control-label"><?php echo _l('visit_planner_B'); ?></label></td>
                                    <td><input type="text" class="form-control" name="diagram_b" id="diagram_b" value="<?= $customer_visit['diagram_b'] ?>" placeholder="<?php echo _l('visit_planner_B'); ?>" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');"></td>
                                    <td><label for="diagram_f" class="control-label"><?php echo _l('visit_planner_F'); ?></label></td>
                                    <td><input type="text" class="form-control" name="diagram_f" id="diagram_f" value="<?= $customer_visit['diagram_f'] ?>" placeholder="<?php echo _l('visit_planner_F'); ?>" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');"></td>
                                </tr>
                                <tr>
                                    <td><label for="avg_power_consumption" class="control-label"><?php echo _l('visit_planner_avg_power_consumption'); ?></label></td>
                                    <td><input type="text" class="form-control" name="avg_power_consumption" id="avg_power_consumption" value="<?= $customer_visit['avg_power_consumption'] ?>" placeholder="<?php echo _l('visit_planner_avg_power_consumption'); ?>" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');"></td>
                                    <td class="text-center"><?php echo _l('visit_planner_kwh'); ?></td>
                                    <td><label for="diagram_c" class="control-label"><?php echo _l('visit_planner_C'); ?></label></td>
                                    <td><input type="text" class="form-control" name="diagram_c" id="diagram_c" value="<?= $customer_visit['diagram_c'] ?>" placeholder="<?php echo _l('visit_planner_C'); ?>" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');"></td>
                                    <td><label for="diagram_g" class="control-label"><?php echo _l('visit_planner_G'); ?></label></td>
                                    <td><input type="text" class="form-control" name="diagram_g" id="diagram_g" value="<?= $customer_visit['diagram_g'] ?>" placeholder="<?php echo _l('visit_planner_G'); ?>" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');"></td>
                                </tr>
                                <tr>
                                    <td><label for="avg_power_consumption" class="control-label"><?php echo _l('visit_planner_grades_of_steel_produce'); ?></label></td>
                                    <td colspan="2">
                                        <select id="grades_of_steel_produce" name="grades_of_steel_produce" data-live-search="true" data-width="100%" class="selectpicker ajax-search">
                                            <option value=""></option>
                                            <option value="<?php echo _l('visit_planner_ms'); ?>" <?= ($customer_visit['grades_of_steel_produce'] == _l('visit_planner_ms')) ? 'selected' : '' ?>><?php echo _l('visit_planner_ms'); ?></option>
                                            <option value="<?php echo _l('visit_planner_ss'); ?>" <?= ($customer_visit['grades_of_steel_produce'] == _l('visit_planner_ss')) ? 'selected' : '' ?>><?php echo _l('visit_planner_ss'); ?></option>
                                            <option value="<?php echo _l('visit_planner_as'); ?>" <?= ($customer_visit['grades_of_steel_produce'] == _l('visit_planner_as')) ? 'selected' : '' ?>><?php echo _l('visit_planner_as'); ?></option>
                                        </select>
                                    </td>
                                    <td><label for="diagram_d" class="control-label"><?php echo _l('visit_planner_D'); ?></label></td>
                                    <td><input type="text" class="form-control" name="diagram_d" id="diagram_d" value="<?= $customer_visit['diagram_d'] ?>" placeholder="<?php echo _l('visit_planner_D'); ?>" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');"></td>
                                    <td><label for="diagram_h" class="control-label"><?php echo _l('visit_planner_H'); ?></label></td>
                                    <td><input type="text" class="form-control" name="diagram_h" id="diagram_h" value="<?= $customer_visit['diagram_h'] ?>" placeholder="<?php echo _l('visit_planner_H'); ?>" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');"></td>
                                </tr>
                                <tr style="border-top : 1px solid ;">
                                    <td><label for="present_lining_method" class="control-label"><?php echo _l('visit_planner_present_lining_method'); ?></label></td>
                                    <td><input type="text" class="form-control" name="present_lining_method" id="present_lining_method" value="<?= $customer_visit['present_lining_method'] ?>" placeholder="<?php echo _l('visit_planner_present_lining_method'); ?>"></td>
                                    <td class="text-center"><?php echo _l('visit_planner_manual'); ?></td>
                                    <td><label for="diagram_c" class="control-label"><?php echo _l('visit_planner_type_of_scrap'); ?></label></td>
                                    <td colspan="3">
                                        <select id="planner_type_of_scrap" name="planner_type_of_scrap" data-live-search="true" data-width="100%" class="selectpicker ajax-search">
                                            <option value=""></option>
                                            <option value="<?php echo _l('visit_planner_shredder'); ?>" <?= ($customer_visit['planner_type_of_scrap'] == _l('visit_planner_shredder')) ? 'selected' : '' ?>><?php echo _l('visit_planner_shredder'); ?></option>
                                            <option value="<?php echo _l('visit_planner_hms'); ?>" <?= ($customer_visit['planner_type_of_scrap'] == _l('visit_planner_hms')) ? 'selected' : '' ?>><?php echo _l('visit_planner_hms'); ?></option>
                                            <option value="<?php echo _l('visit_planner_light_commercial_scrap'); ?>" <?= ($customer_visit['planner_type_of_scrap'] == _l('visit_planner_light_commercial_scrap')) ? 'selected' : '' ?>><?php echo _l('visit_planner_light_commercial_scrap'); ?></option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td><label for="charge_mix_ratio" class="control-label"><?php echo _l('visit_planner_charge_mix_ratio'); ?></label></td>
                                    <td colspan="2"><input type="text" class="form-control" name="charge_mix_ratio" id="charge_mix_ratio" value="<?= $customer_visit['charge_mix_ratio'] ?>" placeholder="<?php echo _l('visit_planner_charge_mix_ratio'); ?>"></td>
                                    <td><label for="cpc" class="control-label"><?php echo _l('visit_planner_cpc'); ?></label></td>
                                    <td colspan="2"><input type="text" class="form-control" name="cpc" id="cpc" value="<?= $customer_visit['cpc'] ?>" placeholder="<?php echo _l('visit_planner_cpc'); ?>" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');"></td>
                                    <td class="text-center"><label for="diagram_g" class="control-label"><?php echo _l('visit_planner_kg_heat'); ?></label></td>
                                </tr>
                                <tr>
                                    <td><label for="met_cock_or_cock_fines" class="control-label"><?php echo _l('visit_planner_met_cock_or_cock_fines'); ?></label></td>
                                    <td><input type="text" class="form-control" name="met_cock_or_cock_fines" id="met_cock_or_cock_fines" value="<?= $customer_visit['met_cock_or_cock_fines'] ?>" placeholder="<?php echo _l('visit_planner_met_cock_or_cock_fines'); ?>" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');"></td>
                                    <td class="text-center"><label for="diagram_g" class="control-label"><?php echo _l('visit_planner_kg_heat'); ?></label></td>
                                    <td><label for="slag_generation" class="control-label"><?php echo _l('visit_planner_slag_generation'); ?></label></td>
                                    <td colspan="2"><input type="text" class="form-control" name="slag_generation" id="slag_generation" value="<?= $customer_visit['slag_generation'] ?>" placeholder="<?php echo _l('visit_planner_slag_generation'); ?>" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');"></td>
                                    <td class="text-center"><label for="diagram_g" class="control-label"><?php echo _l('visit_planner_kg_heat'); ?></label></td>
                                </tr>
                                <tr>
                                    <td><label for="sintering_time" class="control-label"><?php echo _l('visit_planner_sintering_time'); ?></label></td>
                                    <td><input type="text" class="form-control" name="sintering_time" id="sintering_time" value="<?= $customer_visit['sintering_time'] ?>" placeholder="<?php echo _l('visit_planner_sintering_time'); ?>" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');"></td>
                                    <td class="text-center"><label for="diagram_g" class="control-label"><?php echo _l('visit_planner_hrs'); ?></label></td>
                                    <td><label for="power_consumption_for_sintering_heat" class="control-label"><?php echo _l('visit_planner_power_consumption_for_sintering_heat'); ?></label></td>
                                    <td colspan="2"><input type="text" class="form-control" name="power_consumption_for_sintering_heat" id="power_consumption_for_sintering_heat" value="<?= $customer_visit['power_consumption_for_sintering_heat'] ?>" placeholder="<?php echo _l('visit_planner_power_consumption_for_sintering_heat'); ?>" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');"></td>
                                    <td class="text-center"><label for="diagram_g" class="control-label"><?php echo _l('visit_planner_kwh'); ?></label></td>
                                </tr>
                                <tr>
                                    <td><label for="c_at_50_of_bath_metal" class="control-label"><?php echo _l('visit_planner_c_at_50_of_bath_metal'); ?></label></td>
                                    <td><input type="text" class="form-control" name="c_at_50_of_bath_metal" id="c_at_50_of_bath_metal" value="<?= $customer_visit['c_at_50_of_bath_metal'] ?>" placeholder="<?php echo _l('visit_planner_c_at_50_of_bath_metal'); ?>" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');"></td>
                                    <td class="text-center"><label for="diagram_g" class="control-label">%</label></td>
                                    <td><label for="dri_quality" class="control-label"><?php echo _l('visit_planner_dri_quality'); ?></label></td>
                                    <td colspan="2"><input type="text" class="form-control" name="dri_quality" id="dri_quality" value="<?= $customer_visit['dri_quality'] ?>" placeholder="<?php echo _l('visit_planner_dri_quality'); ?>" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');"></td>
                                    <td class="text-center"><label for="diagram_g" class="control-label"><?php echo _l('visit_planner_ratio'); ?></label></td>
                                </tr>
                                <tr>
                                    <td><label for="c_at_80_of_bath_metal" class="control-label"><?php echo _l('visit_planner_c_at_80_of_bath_metal'); ?></label></td>
                                    <td><input type="text" class="form-control" name="c_at_80_of_bath_metal" id="c_at_80_of_bath_metal" value="<?= $customer_visit['c_at_80_of_bath_metal'] ?>" placeholder="<?php echo _l('visit_planner_c_at_80_of_bath_metal'); ?>" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');"></td>
                                    <td class="text-center"><label for="diagram_g" class="control-label">%</label></td>
                                    <td><label for="fem_of_dri" class="control-label"><?php echo _l('visit_planner_fem_of_dri'); ?></label></td>
                                    <td colspan="2"><input type="text" class="form-control" name="fem_of_dri" id="fem_of_dri" value="<?= $customer_visit['fem_of_dri'] ?>" placeholder="<?php echo _l('visit_planner_fem_of_dri'); ?>" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');"></td>
                                    <td class="text-center"><label for="diagram_g" class="control-label">%</label></td>
                                </tr>
                                <tr>
                                    <td><label for="final_c_before_tap" class="control-label"><?php echo _l('visit_planner_final_c_before_tap'); ?></label></td>
                                    <td><input type="text" class="form-control" name="final_c_before_tap" id="final_c_before_tap" value="<?= $customer_visit['final_c_before_tap'] ?>" placeholder="<?php echo _l('visit_planner_final_c_before_tap'); ?>" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');"></td>
                                    <td class="text-center"><label for="diagram_g" class="control-label">%</label></td>
                                    <td><label for="fet_of_dri" class="control-label"><?php echo _l('visit_planner_fet_of_dri'); ?></label></td>
                                    <td colspan="2"><input type="text" class="form-control" name="fet_of_dri" id="fet_of_dri" value="<?= $customer_visit['fet_of_dri'] ?>" placeholder="<?php echo _l('visit_planner_fet_of_dri'); ?>" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');"></td>
                                    <td class="text-center"><label for="diagram_g" class="control-label">%</label></td>
                                </tr>
                                <tr>
                                    <td><label for="mill_scale" class="control-label"><?php echo _l('visit_planner_mill_scale'); ?></label></td>
                                    <td><input type="text" class="form-control" name="mill_scale" id="mill_scale" value="<?= $customer_visit['mill_scale'] ?>" placeholder="<?php echo _l('visit_planner_mill_scale'); ?>" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');"></td>
                                    <td class="text-center"><label for="diagram_g" class="control-label"><?php echo _l('visit_planner_Kg'); ?></label></td>
                                    <td><label for="feo_of_dri" class="control-label"><?php echo _l('visit_planner_feo_of_dri'); ?></label></td>
                                    <td colspan="2"><input type="text" class="form-control" name="feo_of_dri" id="feo_of_dri" value="<?= $customer_visit['feo_of_dri'] ?>" placeholder="<?php echo _l('visit_planner_feo_of_dri'); ?>" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');"></td>
                                    <td class="text-center"><label for="diagram_g" class="control-label">%</label></td>
                                </tr>
                                <tr>
                                    <td><label for="mill_scale" class="control-label"><?php echo _l('visit_planner_opration'); ?></label></td>
                                    <td colspan="6">
                                        <select id="operation" name="operation" data-live-search="true" data-width="100%" class="selectpicker ajax-search">
                                            <option value=""></option>
                                            <option value="<?php echo _l('visit_planner_12_hr_day'); ?>" <?= ($customer_visit['operation'] == _l('visit_planner_12_hr_day')) ? 'selected' : '' ?>><?php echo _l('visit_planner_12_hr_day'); ?></option>
                                            <option value="<?php echo _l('visit_planner_16_18_hr_day'); ?>" <?= ($customer_visit['operation'] == _l('visit_planner_16_18_hr_day')) ? 'selected' : '' ?>><?php echo _l('visit_planner_16_18_hr_day'); ?></option>
                                            <option value="<?php echo _l('visit_planner_24_7'); ?>" <?= ($customer_visit['operation'] == _l('visit_planner_24_7')) ? 'selected' : '' ?>><?php echo _l('visit_planner_24_7'); ?></option>
                                            <option value="<?php echo _l('visit_planner_24_6_or_pls_mention'); ?>" <?= ($customer_visit['operation'] == _l('visit_planner_24_6_or_pls_mention')) ? 'selected' : '' ?>><?php echo _l('visit_planner_24_6_or_pls_mention'); ?></option>
                                        </select>
                                    </td>
                                </tr>
                                <tr style="border-top : 1px solid ;">
                                    <td><label for="specific_problem_customer_face" class="control-label"><?php echo _l('visit_planner_specific_problem_customer_face'); ?></label></td>
                                    <td colspan="6"><textarea class="form-control" name="specific_problem_customer_face" id="specific_problem_customer_face" placeholder="<?php echo _l('visit_planner_specific_problem_customer_face'); ?>"><?= $customer_visit['specific_problem_customer_face'] ?></textarea></td>
                                </tr>
                                <tr style="border-top : 1px solid ;">
                                    <td><label for="reason_for_lining_breaking" class="control-label"><?php echo _l('visit_planner_reason_for_lining_breaking'); ?></label></td>
                                    <td colspan="6"><textarea class="form-control" name="reason_for_lining_breaking" id="reason_for_lining_breaking" placeholder="<?php echo _l('visit_planner_reason_for_lining_breaking'); ?>"><?= $customer_visit['reason_for_lining_breaking'] ?></textarea></td>
                                </tr>
                                <tr style="border-top : 1px solid ;">
                                    <td><label for="specific_erosion_pattern" class="control-label"><?php echo _l('visit_planner_specific_erosion_pattern'); ?></label></td>
                                    <td colspan="6"><textarea class="form-control" name="specific_erosion_pattern" id="specific_erosion_pattern" placeholder="<?php echo _l('visit_planner_specific_erosion_pattern'); ?>"><?= $customer_visit['specific_erosion_pattern'] ?></textarea></td>
                                </tr>
                                <tr style="border-top : 1px solid ;">
                                    <td><label for="customer_expectation" class="control-label"><?php echo _l('visit_planner_customer_expectation'); ?></label></td>
                                    <td colspan="6"><textarea class="form-control" name="customer_expectation" id="customer_expectation" placeholder="<?php echo _l('visit_planner_customer_expectation'); ?>"><?= $customer_visit['customer_expectation'] ?></textarea></td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <hr class="hr-panel-separator" />

                    <div class="panel-body dynamic_form_main" <?= ($customer_visit['show_inquiry_form'] == 'yes') ? 'style="display:block;"' : 'style="display:none;"' ?>>
                        <div class="table-responsive s_table">
                            <table class="table invoice-items-table items table-main-invoice-edit has-calculations no-mtop dynamic_table">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th align="left"><?php echo _l('visit_planner_furnace_capacity'); ?></th>
                                    <th align="left"><?php echo _l('visit_planner_kw_rating'); ?></th>
                                    <th align="left"><?php echo _l('visit_planner_Furnace_oem'); ?></th>
                                    <th align="left"><?php echo _l('visit_planner_tap_to_tap_time'); ?></th>
                                    <th align="left"><?php echo _l('visit_planner_present_lining_material'); ?></th>
                                    <th align="left"><?php echo _l('visit_planner_supplier'); ?></th>
                                    <th align="left"><?php echo _l('visit_planner_consumtion_for_new_lining'); ?></th>
                                    <th align="left"><?php echo _l('visit_planner_consumption_for_side'); ?></th>
                                    <th align="left"><?php echo _l('visit_planner_new_lining_life'); ?></th>
                                    <th align="left"><?php echo _l('visit_planner_side_lining_life'); ?></th>
                                    <th align="left"><?php echo _l('visit_planner_total_no_of_side_lining'); ?></th>
                                    <th align="left"><?php echo _l('visit_planner_total_no_of_heats'); ?></th>
                                    <!-- <th align="center"><i class="fa fa-cog"></i></th>-->
                                </tr>
                                </thead>
                                <tbody class="dynamic_form">
                                <?php if($customer_visit['show_inquiry_form'] == 'yes'){
                                    if(!empty($customer_visit['total_no_of_panels'])){
                                        $inquiry = $this->db->where_in('customer_visit_id', $customer_visit['id'])->get('tblcustomer_visit_inquiry')->result_array();
                                        sort($inquiry);
                                        $j=0;
                                        for($i=1;$i<=$customer_visit['total_no_of_panels'];$i++){ ?>
                                            <tr>
                                                <td><?= $i ?></td>
                                                <td><input type="text" class="form-control" name="furnace_capacity_<?= $i ?>" value="<?= $inquiry[$j]['furnace_capacity'] ?>" id="furnace_capacity_<?= $i ?>" placeholder="Furnace Capacity" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');"></td>
                                                <td><input type="text" class="form-control" name="kw_rating_<?= $i ?>" id="kw_rating_<?= $i ?>" value="<?= $inquiry[$j]['kw_rating'] ?>" placeholder="KW rating" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');"></td>
                                                <td><input type="text" class="form-control" name="Furnace_oem_<?= $i ?>" id="Furnace_oem_<?= $i ?>" value="<?= $inquiry[$j]['Furnace_oem'] ?>" placeholder="Furnace OEM" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');"></td>
                                                <td><input type="text" class="form-control" name="tap_to_tap_time_<?= $i ?>" id="tap_to_tap_time_<?= $i ?>" value="<?= $inquiry[$j]['tap_to_tap_time'] ?>" placeholder="Tap to tap time" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');"></td>
                                                <td><input type="text" class="form-control" name="present_lining_material_<?= $i ?>" id="present_lining_material_<?= $i ?>" value="<?= $inquiry[$j]['present_lining_material'] ?>" placeholder="Present Lining material"></td>
                                                <td><input type="text" class="form-control" name="supplier_<?= $i ?>" id="supplier_<?= $i ?>" value="<?= $inquiry[$j]['supplier'] ?>" placeholder="Supplier"></td>
                                                <td><input type="text" class="form-control" name="consumtion_for_new_lining_<?= $i ?>" id="consumtion_for_new_lining_<?= $i ?>" value="<?= $inquiry[$j]['consumtion_for_new_lining'] ?>" placeholder="Consumption for New lining" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');"></td>
                                                <td><input type="text" class="form-control" name="consumption_for_side_<?= $i ?>" id="consumption_for_side_<?= $i ?>" value="<?= $inquiry[$j]['consumption_for_side'] ?>" placeholder="Consumption for Side Lining/patching" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');"></td>
                                                <td><input type="text" class="form-control" name="new_lining_life_<?= $i ?>" id="new_lining_life_<?= $i ?>" value="<?= $inquiry[$j]['new_lining_life'] ?>" placeholder="New Lining Life (Side + Bottom)" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');"></td>
                                                <td><input type="text" class="form-control" name="side_lining_life_<?= $i ?>" id="side_lining_life_<?= $i ?>" value="<?= $inquiry[$j]['side_lining_life'] ?>" placeholder="Side Lining Life (Only Side)" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');"></td>
                                                <td><input type="text" class="form-control" name="total_no_of_side_lining_<?= $i ?>" id="total_no_of_side_lining_<?= $i ?>" value="<?= $inquiry[$j]['total_no_of_side_lining'] ?>" placeholder="Total No. of Side lining/ New Lining" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');"></td>
                                                <td><input type="text" class="form-control" name="total_no_of_heats_<?= $i ?>" id="total_no_of_heats_<?= $i ?>" value="<?= $inquiry[$j]['total_no_of_heats'] ?>" placeholder="Total No. of Heats / Furnace/ day" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');"></td>
                                            </tr>
                                            <?php $j++;
                                        }
                                    }
                                } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>

                <div class="btn-bottom-pusher"></div>
                <div class="btn-bottom-toolbar text-right">
                    <a type="button" href="<?php echo admin_url('Visit_Planner/list_customer_visit/'.$visit_planner_id); ?>" class="btn-tr btn btn-default mright5 text-right">Cancel</a>
                    <div class="btn-group dropup">
                        <button type="submit" class="btn-tr btn btn-primary booking_unfix_transaction-submit"><?php echo _l('submit'); ?></button>
                    </div>
                </div>
            </div>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>
<?php init_tail(); ?>
<script>
    init_ajax_search('contacts','#contacts.ajax-search');

    $(document).on('change', '#rel_type', function() {
        var rel_type = $(this).val();
        if(rel_type == 'Lead'){
            $('.lead_field').css('display','block');
            $('.customer_field').css('display','none');
        }else{
            $('.lead_field').css('display','none');
            $('.customer_field').css('display','block');
        }
    });

    $('#subject,#visit_planner_to,#visit_planner_city,#visit_planner_state').bind('keyup blur', function () {
            var node = $(this);
            node.val(node.val().replace(/[^a-zA-Z ]/g, ''));
        }
    );

    $('#total_no_of_panels').change(function() {
        var total_no = $(this).val();
        if(total_no != ''){
            $('.dynamic_form').html('');
            $(".dynamic_form_main").css('display','block');
            for(var i=1;i<=total_no;i++){
                let html = '';
                html = '<tr>'+
                    '<td>'+ i +'</td>'+
                    '<td><input type="text" class="form-control" name="furnace_capacity_'+i+'" id="furnace_capacity_'+i+'" placeholder="<?php echo _l('visit_planner_furnace_capacity'); ?>" oninput="this.value = this.value.replace(/[^0-9.]/g, \'\').replace(/(\\..*)\\./g, \'$1\');"></td>'+
                    '<td><input type="text" class="form-control" name="kw_rating_'+i+'" id="kw_rating_'+i+'" placeholder="<?php echo _l('visit_planner_kw_rating'); ?>" oninput="this.value = this.value.replace(/[^0-9.]/g, \'\').replace(/(\\..*)\\./g, \'$1\');"></td>'+
                    '<td><input type="text" class="form-control" name="Furnace_oem_'+i+'" id="Furnace_oem_'+i+'" placeholder="<?php echo _l('visit_planner_Furnace_oem'); ?>" oninput="this.value = this.value.replace(/[^0-9.]/g, \'\').replace(/(\\..*)\\./g, \'$1\');"></td>'+
                    '<td><input type="text" class="form-control" name="tap_to_tap_time_'+i+'" id="tap_to_tap_time_'+i+'" placeholder="<?php echo _l('visit_planner_tap_to_tap_time'); ?>" oninput="this.value = this.value.replace(/[^0-9.]/g, \'\').replace(/(\\..*)\\./g, \'$1\');"></td>'+
                    '<td><input type="text" class="form-control" name="present_lining_material_'+i+'" id="present_lining_material_'+i+'" placeholder="<?php echo _l('visit_planner_present_lining_material'); ?>"></td>'+
                    '<td><input type="text" class="form-control" name="supplier_'+i+'" id="supplier_'+i+'" placeholder="<?php echo _l('visit_planner_supplier'); ?>"></td>'+
                    '<td><input type="text" class="form-control" name="consumtion_for_new_lining_'+i+'" id="consumtion_for_new_lining_'+i+'" placeholder="<?php echo _l('visit_planner_consumtion_for_new_lining'); ?>" oninput="this.value = this.value.replace(/[^0-9.]/g, \'\').replace(/(\\..*)\\./g, \'$1\');"></td>'+
                    '<td><input type="text" class="form-control" name="consumption_for_side_'+i+'" id="consumption_for_side_'+i+'" placeholder="<?php echo _l('visit_planner_consumption_for_side'); ?>" oninput="this.value = this.value.replace(/[^0-9.]/g, \'\').replace(/(\\..*)\\./g, \'$1\');"></td>'+
                    '<td><input type="text" class="form-control" name="new_lining_life_'+i+'" id="new_lining_life_'+i+'" placeholder="<?php echo _l('visit_planner_new_lining_life'); ?>" oninput="this.value = this.value.replace(/[^0-9.]/g, \'\').replace(/(\\..*)\\./g, \'$1\');"></td>'+
                    '<td><input type="text" class="form-control" name="side_lining_life_'+i+'" id="side_lining_life_'+i+'" placeholder="<?php echo _l('visit_planner_side_lining_life'); ?>" oninput="this.value = this.value.replace(/[^0-9.]/g, \'\').replace(/(\\..*)\\./g, \'$1\');"></td>'+
                    '<td><input type="text" class="form-control" name="total_no_of_side_lining_'+i+'" id="total_no_of_side_lining_'+i+'" placeholder="<?php echo _l('visit_planner_total_no_of_side_lining'); ?>" oninput="this.value = this.value.replace(/[^0-9.]/g, \'\').replace(/(\\..*)\\./g, \'$1\');"></td>'+
                    '<td><input type="text" class="form-control" name="total_no_of_heats_'+i+'" id="total_no_of_heats_'+i+'" placeholder="<?php echo _l('visit_planner_total_no_of_heats'); ?>" oninput="this.value = this.value.replace(/[^0-9.]/g, \'\').replace(/(\\..*)\\./g, \'$1\');"></td>'+
                    '</tr>';
                $('.dynamic_form').append(html);
            }
        }else{
            let html = ' ';
            $('.dynamic_form').html(html);
            $(".dynamic_form_main").css('display','none');
        }
    });

    var rel_type = $('#rel_type').val();
    if(rel_type != ''){
        if(rel_type == 'Lead'){
            $('.lead_field').css('display','block');
            $('.customer_field').css('display','none');
        }else{
            $('.lead_field').css('display','none');
            $('.customer_field').css('display','block');
        }
    }

    $('input[name=show_inquiry_form]').change(function() {
        let form = $(this).val();
        if (form == "yes") {
            $(".visit_planner_enquiry_form").css('display','block');
            $(".dynamic_form_main").css('display','block');
        } else {
            $(".visit_planner_enquiry_form").css('display','none');
            $(".dynamic_form_main").css('display','none');
        }
    });

    $(document).on('click', '.remove-old-img-button', function() {
        // remove the select element with the id 'question'
        $(this).parent('div').parent('div').parent('div').remove();
        var img = $(this).attr('data-img');
        var old_img = '';
        old_img = '<input type="hidden" name="old_files[]" value="'+img+'">';
        $('.old_img_data').append(old_img);
    });

    $(document).on('change', '#leadid', function() {
        var leadid = $(this).val();
        $.ajax({
            url: '<?php echo admin_url() ?>Visit_Planner/fetch_lead/',
            type: "POST",
            data: {
                leadid:leadid
            },
            dataType: "json",
            success: function (res) {
                $('#visit_planner_to').val(res.lead.name);
                $('#visit_planner_company_name').val(res.lead.company);
            }
        });
    });
    $(document).on('change', '#clientid', function() {
        var clientid = $(this).val();
        $.ajax({
            url: '<?php echo admin_url() ?>Visit_Planner/fetch_customer/',
            type: "POST",
            data: {
                clientid:clientid
            },
            dataType: "json",
            success: function (res) {
                $('#visit_planner_to').val(res.contact.firstname+' '+res.contact.lastname);
                $('#visit_planner_address').val(res.customer.address);
                $('#visit_planner_company_name').val(res.customer.company);
                $('#visit_planner_city').val(res.customer.city);
                $('#visit_planner_state').val(res.customer.state);
                $('#visit_planner_zip').val(res.customer.zip);
                $('#visit_planner_email').val(res.contact.email);
                $('#visit_planner_phone').val(res.contact.phonenumber);
                $('#visit_planner_country').val(res.customer.country).trigger('change');
            }
        });
    });

    $('#visit_planner-form').validate({
        rules: {
            subject: "required",
            type: "required",
            datetime: "required",
            rel_type: "required",
            visit_planner_email: {
                email: true
            },
            visit_planner_to : "required",
            assigned : "required",
        },
        message: {
            subject: 'This field is required.',
        }
    });

    $('#clientid').change(function() {
        var client = $(this).val();
        if (client != '') {
            $.ajax({
                url: '<?php echo base_url() . "admin/Visit_Planner/getContactByClient"; ?>',
                type: 'post',
                data: {
                    client: client
                },
                dataType: 'json',
                success: function(response) {
                    // console.log(response);
                    var len = response.length;
                    $("#drp_contacts").empty();
                    $("#drp_contacts").append("<option value=''>Select Contacts</option>");
                    for (var i = 0; i < len; i++) {
                        var id = response[i]['id'];
                        var firstname = response[i]['firstname'];
                        var lastname = response[i]['lastname'];
                        var phonenumber = response[i]['phonenumber'];
                        $("#drp_contacts").append("<option value='" + id + "'>" + firstname + ' ' + lastname + '(' + phonenumber + ')' +"</option>");
                    }
                }
            });
        } else {
            $("#drp_contacts").html("<option value=''>Select Contacts</option>");
        }
    });
    $('#clientid').trigger('change');

    $('#product_explain').on('change', function () {
        let product = $(this).val();
        if(product == 'Other'){
            $('.show_p_other').css('display','block');
        }else{
            $('.show_p_other').css('display','none');
        }
    });
    $('#opportunity').on('change', function () {
        let oppty = $(this).val();
        if(oppty == 'Other'){
            $('.show_o_other').css('display','block');
        }else{
            $('.show_o_other').css('display','none');
        }
    });

    function setCurrentDateTime() {
        const now = new Date();
        const year = now.getFullYear();
        const month = String(now.getMonth() + 1).padStart(2, '0');
        const day = String(now.getDate()).padStart(2, '0');
        const hours = String(now.getHours()).padStart(2, '0');
        const minutes = String(now.getMinutes()).padStart(2, '0');

        // Create formatted string YYYY-MM-DDTHH:MM for datetime-local input
        const formattedDateTime = `${year}-${month}-${day}T${hours}:${minutes}`;

        // Set the value of the input field
        document.getElementById('visit_planner_nxt_followup_date').value = formattedDateTime;
        document.getElementById('datetime').value = formattedDateTime;
    }

    // Call the function to set current date and time when the page loads
    window.onload = setCurrentDateTime;
</script>
<style>
    .error {
        color: red;
    }
</style>
<style>
    .enquiry_form tr td{
        border: 1px solid #e2e8f0;
    }
    .dynamic_table tr td{
        border: 1px solid #e2e8f0;
    }
</style>
<style>
    .image-area {
        position: relative;
        width: 50%;
        background: #333;
    }
    .image-area img{
        max-width: 100%;
        height: auto;
    }
    .remove-image {
        display: none;
        position: absolute;
        top: -10px;
        right: -10px;
        border-radius: 10em;
        padding: 0px 3px 0px;
        text-decoration: none;
        font: 700 13px/13px sans-serif;
        background: #555;
        border: 3px solid #fff;
        color: #FFF;
        box-shadow: 0 2px 6px rgba(0,0,0,0.5), inset 0 2px 4px rgba(0,0,0,0.3);
        text-shadow: 0 1px 2px rgba(0,0,0,0.5);
        -webkit-transition: background 0.5s;
        transition: background 0.5s;
    }
    .remove-image:hover {
        background: #E54E4E;
        padding: 0px 3px 0px;
        top: -11px;
        right: -11px;
        cursor: pointer;
    }
    .remove-image:active {
        background: #E54E4E;
        top: -10px;
        right: -11px;
    }
    .document-area {
        position: relative;
        width: auto;
        background: #333;
    }
</style>

</body>

</html>