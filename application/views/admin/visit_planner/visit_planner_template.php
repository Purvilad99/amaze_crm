<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div class="panel_s invoice accounting-template">
<div class="additional"></div>

    <div class="panel-body">
        <div class="row">
            <div class="col-md-6 border-right">

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <?php $value = $visit_planner['subject']; ?>
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
                                <option value="<?php echo _l('visit_planner_planned_visit'); ?>" <?= ($visit_planner['type'] == _l('visit_planner_planned_visit')) ? 'selected' : '' ?>><?php echo _l('visit_planner_planned_visit'); ?></option>
                                <option value="<?php echo _l('visit_planner_unplanned_visit'); ?>" <?= ($visit_planner['type'] == _l('visit_planner_unplanned_visit')) ? 'selected' : '' ?>><?php echo _l('visit_planner_unplanned_visit'); ?></option>
                            </select>
                            <label id="type-error" class="error hide" for="type">This field is required.</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <?php $value = (!empty($visit_planner['datetime'])) ? $visit_planner['datetime'] : date('d-m-Y h:m A'); ?>
                            <label for="time" class="control-label"><?php echo _l('visit_planner_date'); ?><span class="text-danger"> *</span></label>
                            <input class="form-control form-check-input" type="datetime-local" id="visit_planner_datetime" name="datetime" value="<?= $value ?>">
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="type" class="control-label"><?php echo _l('proposal_related'); ?></label><span class="text-danger"> *</span>
                    <select id="rel_type" name="rel_type" data-live-search="true" data-width="100%" class="selectpicker ajax-search">
                        <option value=""></option>
                        <option value="<?php echo _l('visit_planner_lead'); ?>" <?= ($visit_planner['rel_type'] == _l('visit_planner_lead')) ? 'selected' : '' ?>><?php echo _l('visit_planner_lead'); ?></option>
                        <option value="<?php echo _l('visit_planner_customer'); ?>" <?= ($visit_planner['rel_type'] == _l('visit_planner_customer')) ? 'selected' : '' ?>><?php echo _l('visit_planner_customer'); ?></option>
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
                                    <option value="<?php echo $customer['userid']; ?>" <?= ($visit_planner['clientid'] == $customer['userid']) ? 'selected' : '' ?>><?php echo $customer['company']; ?></option>
                                <?php } ?>
                            </select>
                            <label id="client-error" class="error hide" for="location">This field is required.</label>
                        </div>
                    </div>
                    <div class="col-md-6 show_contacts">
                        <div class="form-group">
                            <label for="contacts" class="control-label"><?php echo _l('visit_planner_contacts'); ?><span class="text-danger"> *</span></label>
                            <select id="drp_contacts" name="contacts[]" multiple data-live-search="true" data-width="100%" class="selectpicker ajax-search">
                                <option value=""></option>
                            </select>
                            <label id="client-error" class="error hide" for="location">This field is required.</label>
                        </div>
                    </div>
                </div>

                <div class="form-group lead_field" style="display:none;">
                    <label for="clientid" class="control-label"><?php echo _l('visit_planner_lead'); ?><span class="text-danger"> *</span></label>
                    <?php
                    $leads = $this->db->get('tblleads')->result_array();
                    ?>
                    <select id="leadid" name="leadid" data-live-search="true" data-width="100%" class="selectpicker ajax-search">
                        <option value=""></option>
                        <?php foreach ($leads as $lead) { ?>
                            <option value="<?php echo $lead['id']; ?>" <?= ($visit_planner['leadid'] == $lead['id']) ? 'selected' : '' ?>><?php echo $lead['name']; ?></option>
                        <?php } ?>
                    </select>
                    <label id="client-error" class="error hide" for="location">This field is required.</label>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group no-mbot">
                            <label for="tags" class="control-label"><i class="fa fa-tag" aria-hidden="true"></i>
                                <?php echo _l('tags'); ?></label>
                            <input type="text" class="tagsinput" id="tags" name="tags"
                                   value="<?= $visit_planner['tags'] ?>"
                                   data-role="tagsinput">
                        </div>
                    </div>
                </div>

            </div>
            <div class="col-md-6">
                <div class="row">
                    <div class="col-md-6">
                        <?php
                        $staff = $this->db->get('tblstaff')->result_array();
                        $selected = !isset($visit_planner) && get_option('automatically_set_logged_in_staff_sales_agent') == '1' ? get_staff_user_id() : '';
                        foreach ($staff as $member) {
                            if (isset($visit_planner)) {
                                if ($visit_planner['assigned'] == $member['staffid']) {
                                    $selected = $member['staffid'];
                                }
                            }
                        }
                        echo render_select('assigned', $staff, ['staffid', ['firstname', 'lastname']], 'proposal_assigned', $selected);
                        ?>
                    </div>
                    <div class="col-md-6">
                        <?php $value = $visit_planner['visit_planner_company_name']; ?>
                        <?php echo render_input('visit_planner_company_name', 'Company', $value); ?>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <?php $value = $visit_planner['visit_planner_to']; ?>
                            <label for="time" class="control-label"><?php echo _l('proposal_to'); ?><span class="text-danger"> *</span></label>
                            <input class="form-control form-check-input" type="text" id="visit_planner_to" name="visit_planner_to" value="<?= $value ?>">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <?php $value = $visit_planner['gstno']; ?>
                            <label for="time" class="control-label"><?php echo _l('client_gstno'); ?></label>
                            <input class="form-control form-check-input" type="text" id="visit_planner_gstno" name="visit_planner_gstno" value="<?= $value ?>">
                        </div>
                    </div>
                </div>

                <?php $value = $visit_planner['visit_planner_address']; ?>
                <?php echo render_textarea('visit_planner_address', 'proposal_address', $value); ?>

                <div class="row">
                    <div class="col-md-6">
                        <?php $value = $visit_planner['visit_planner_city']; ?>
                        <?php echo render_input('visit_planner_city', 'billing_city', $value); ?>
                    </div>
                    <div class="col-md-6">
                        <?php $value = $visit_planner['visit_planner_state']; ?>
                        <?php echo render_input('visit_planner_state', 'billing_state', $value); ?>
                    </div>
                    <div class="col-md-6">
                        <?php $countries = get_all_countries(); ?>
                        <?php $selected  = $visit_planner['visit_planner_country']; ?>
                        <?php echo render_select('visit_planner_country', $countries, ['country_id', ['short_name'], 'iso2'], 'billing_country', $selected); ?>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <?php $value = $visit_planner['visit_planner_zip']; ?>
                            <label for="time" class="control-label"><?php echo _l('billing_zip'); ?></label>
                            <input class="form-control form-check-input" type="text" id="visit_planner_zip" name="visit_planner_zip" value="<?= $value ?>" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <?php $value = $visit_planner['visit_planner_email']; ?>
                        <?php echo render_input('visit_planner_email', 'proposal_email', $value); ?>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <?php $value = $visit_planner['visit_planner_phone']; ?>
                            <label for="time" class="control-label"><?php echo _l('proposal_phone'); ?></label>
                            <input class="form-control form-check-input" type="text" id="visit_planner_phone" name="visit_planner_phone" value="<?= $value ?>" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="btn-bottom-pusher"></div>
<div class="btn-bottom-toolbar text-right">
    <a type="button" href="<?php echo admin_url('Visit_Planner/index'); ?>" class="btn-tr btn btn-default mright5 text-right">Cancel</a>
    <div class="btn-group dropup">
        <button type="submit" class="btn-tr btn btn-primary booking_unfix_transaction-submit"><?php echo _l('submit'); ?></button>
    </div>
</div>


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