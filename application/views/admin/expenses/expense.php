<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="tw-max-w-4xl tw-mx-auto">
            <h4 class="tw-mt-0 tw-font-bold tw-text-lg tw-text-neutral-700">
                <?= e($title); ?>
            </h4>
            <div class="horizontal-scrollable-tabs">
                <div class="scroller arrow-left"><i class="fa fa-angle-left"></i></div>
                <div class="scroller arrow-right"><i class="fa fa-angle-right"></i></div>
                <div class="horizontal-tabs">
                    <ul class="nav nav-tabs nav-tabs-horizontal nav-tabs-segmented tw-mb-3" role="tablist">
                        <li role="presentation" class="active">
                            <a href="#tab_expense" aria-controls="tab_expense" role="tab" data-toggle="tab">
                                <?= _l('expense'); ?>
                            </a>
                        </li>
                        <!--<li role="presentation">
                            <a href="#tab_advanced_options" aria-controls="tab_advanced_options" role="tab"
                                data-toggle="tab">
                                <?/*= _l('advanced_options'); */?>
                            </a>
                        </li>-->
                    </ul>
                </div>
            </div>
            <?php if (isset($expense)) {
                echo form_hidden('is_edit', 'true');
            } ?>
            <?= form_open_multipart($this->uri->uri_string(), ['id' => 'expense-form', 'class' => 'dropzone dropzone-manual']); ?>
            <div class="panel_s">
                <div class="panel-body">
                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane active" id="tab_expense">
                            <?php hooks()->do_action('before_expense_form_name', isset($expense) ? $expense : null); ?>

                            <?php $selected = (isset($expense) ? $expense->category : '');
                            if (is_admin() || get_option('staff_members_create_inline_expense_categories') == '1') {
                                echo render_select_with_input_group('category', $categories, ['name', 'name'], 'expense_category', $selected, '<div class="input-group-btn"><a href="#" class="btn btn-default" onclick="new_category();return false;"><i class="fa fa-plus"></i></a></div>');
                            } else {
                                echo render_select('category', $categories, ['name', 'name'], 'expense_category', $selected);
                            } ?>

                            <div class="row emp_div" <?= ($expense->category == 'Employee') ? 'style="display:block;"' : 'style="display:none;"' ?>>
                                <div class="col-md-12">
                                    <?php
                                    $staff = $this->db->get('tblstaff')->result_array();
                                    $selected = !isset($expense) && get_option('automatically_set_logged_in_staff_sales_agent') == '1' ? get_staff_user_id() : '';
                                    foreach ($staff as $member) {
                                        if (isset($expense)) {
                                            if ($expense->employee == $member['staffid']) {
                                                $selected = $member['staffid'];
                                            }
                                        }
                                    }
                                    echo render_select('employee', $staff, ['staffid', ['firstname', 'lastname']], 'expense_employee', $selected);
                                    ?>
                                </div>
                            </div>

                            <!--<i class="fa-regular fa-circle-question pull-left tw-mt-0.5 tw-mr-1" data-toggle="tooltip"
                            data-title="<?php /*echo _l('expense_name_help'); */?> - <?php /*echo _l('expense_field_billable_help', _l('expense_name')); */?>"></i>
                        <?php /*$value = (isset($expense) ? $expense->expense_name : ''); */?>
                        --><?php /*echo render_input('expense_name', 'expense_name', $value); */?>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="type" class="control-label"><?php echo _l('expense_type'); ?></label>
                                        <select id="type" name="type" data-live-search="true" data-width="100%" class="selectpicker projects ajax-search">
                                            <option value=""></option>
                                            <option value="<?php echo _l('expense_type_auto'); ?>" <?= ($expense->type == _l('expense_type_auto')) ? 'selected' : '' ?>><?php echo _l('expense_type_auto'); ?></option>
                                            <option value="<?php echo _l('expense_type_bus'); ?>" <?= ($expense->type == _l('expense_type_bus')) ? 'selected' : '' ?>><?php echo _l('expense_type_bus'); ?></option>
                                            <option value="<?php echo _l('expense_type_train'); ?>" <?= ($expense->type == _l('expense_type_train')) ? 'selected' : '' ?>><?php echo _l('expense_type_train'); ?></option>
                                            <option value="<?php echo _l('expense_type_flight'); ?>" <?= ($expense->type == _l('expense_type_flight')) ? 'selected' : '' ?>><?php echo _l('expense_type_flight'); ?></option>
                                            <option value="<?php echo _l('expense_type_taxi'); ?>" <?= ($expense->type == _l('expense_type_taxi')) ? 'selected' : '' ?>><?php echo _l('expense_type_taxi'); ?></option>
                                            <option value="<?php echo _l('expense_type_hotel'); ?>" <?= ($expense->type == _l('expense_type_hotel')) ? 'selected' : '' ?>><?php echo _l('expense_type_hotel'); ?></option>
                                            <option value="<?php echo _l('expense_type_breakfast'); ?>" <?= ($expense->type == _l('expense_type_breakfast')) ? 'selected' : '' ?>><?php echo _l('expense_type_breakfast'); ?></option>
                                            <option value="<?php echo _l('expense_type_lunch'); ?>" <?= ($expense->type == _l('expense_type_lunch')) ? 'selected' : '' ?>><?php echo _l('expense_type_lunch'); ?></option>
                                            <option value="<?php echo _l('expense_type_dinner'); ?>" <?= ($expense->type == _l('expense_type_dinner')) ? 'selected' : '' ?>><?php echo _l('expense_type_dinner'); ?></option>
                                            <option value="<?php echo _l('expense_type_miscellaneous'); ?>" <?= ($expense->type == _l('expense_type_miscellaneous')) ? 'selected' : '' ?>><?php echo _l('expense_type_miscellaneous'); ?></option>
                                        </select>
                                        <label id="type-error" class="error hide" for="type">This field is required.</label>
                                    </div>
                                </div>
                            </div>

                            <?php $value = (!empty($expense->date)) ? $expense->date : date('d-m-Y'); ?>
                            <?php echo render_date_input('date', 'expense_add_edit_date', $value, $date_attrs); ?>

                            <div class="row date_div" <?= ($expense->type == 'Hotel') ? 'style="display:block;"' : 'style="display:none;"' ?>>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <?php $value = (!empty($expense->fromdate)) ? $expense->fromdate : date('d-m-Y h:m A'); ?>
                                        <label for="type" class="control-label"><?php echo _l('expense_fromdate'); ?></label>
                                        <input class="form-control form-check-input" type="date" id="expense_fromdate" name="fromdate" value="<?= $value ?>">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <?php $value = (!empty($expense->todate)) ? $expense->todate : date('d-m-Y h:m A'); ?>
                                        <label for="time" class="control-label"><?php echo _l('expense_todate'); ?></label>
                                        <input class="form-control form-check-input" type="date" id="expense_todate" name="todate" value="<?= $value ?>">
                                    </div>
                                </div>
                            </div>

                            <div class="row location_div" <?= ($expense->type == 'Bus' || $expense->type == 'Train' || $expense->type == 'Flight') ? 'style="display:block;"' : 'style="display:none;"' ?>>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <?php $value = (!empty($expense->from_location)) ? $expense->from_location : '' ?>
                                        <label for="type" class="control-label"><?php echo _l('expense_from_location'); ?></label>
                                        <input class="form-control form-check-input" type="text" id="expense_from_location" name="from_location" value="<?= $value ?>">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <?php $value = (!empty($expense->to_location)) ? $expense->to_location : '' ?>
                                        <label for="time" class="control-label"><?php echo _l('expense_to_location'); ?></label>
                                        <input class="form-control form-check-input" type="text" id="expense_to_location" name="to_location" value="<?= $value ?>">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <?php $value = (!empty($expense->billable)) ? $expense->billable : '0' ?>
                                    <input type="checkbox" id="expense_billable" name="billable" value="<?= $value ?>" <?= ($expense->billable == '1') ? 'checked' : '' ?>>
                                    <label for="billable" class="control-label"><?php echo _l('expense_billable'); ?></label>
                                </div>
                            </div>

                            <?php if (isset($expense) && $expense->attachment !== '') { ?>
                                <div class="row">
                                    <div class="col-md-10">
                                        <i class="<?php echo get_mime_class($expense->filetype); ?>"></i> <a
                                                href="<?php echo site_url('download/file/expense/' . $expense->id); ?>"><?php echo $expense->attachment; ?></a>
                                    </div>
                                    <?php if ($expense->attachment_added_from == get_staff_user_id() || is_admin()) { ?>
                                        <div class="col-md-2 text-right">
                                            <a href="<?php echo admin_url('expenses/delete_expense_attachment/' . $expense->id); ?>"
                                               class="text-danger _delete"><i class="fa fa fa-times"></i></a>
                                        </div>
                                    <?php } ?>
                                </div></br>
                            <?php } ?>
                            <?php if (!isset($expense) || (isset($expense) && $expense->attachment == '')) { ?>
                                <div id="dropzoneDragArea" class="dz-default dz-message recepit_div" style="display: none;">
                                    <span><?php echo _l('expense_add_edit_attach_receipt'); ?></span>
                                </div>

                                <div class="dropzone-previews"></div>
                                <!--<div class="row recepit_div" style="display: none;">
                            <div class="col-md-12 form-group">
                                <label for="billable" class="control-label"><?php /*echo _l('expense_add_edit_attach_receipt'); */?></label>
                                <input type="file" class="form-control" name="receipt" id="receipt" style="display: block !important;">
                            </div>
                        </div>-->
                            <?php } ?>

                            <i class="fa-regular fa-circle-question pull-left tw-mt-0.5 tw-mr-1" data-toggle="tooltip"
                               data-title="<?php echo _l('expense_field_billable_help', _l('expense_add_edit_note')); ?>"></i>
                            <?php $value = (isset($expense) ? $expense->note : ''); ?>
                            <?php echo render_textarea('note', 'expense_add_edit_note', $value, ['rows' => 4], []); ?>

                            <?php $rel_id = (isset($expense) ? $expense->expenseid : false); ?>
                            <?php echo render_custom_fields('expenses', $rel_id); ?>

                            <div class="row">
                                <div class="col-md-12 form-group">
                                    <label for="currency" class="control-label"><?php echo _l('expense_currency'); ?></label>
                                    <select id="currency" name="currency" data-live-search="true" data-width="100%" class="selectpicker projects ajax-search">
                                        <option value=""></option>
                                        <?php
                                        foreach ($currencies as $curcy) { ?>
                                            <option value="<?= $curcy['id'] ?>" <?= ($expense->currency == $curcy['id']) ? 'selected' : '' ?>><?= $curcy['name'] ?>&nbsp;&nbsp;<?= $curcy['symbol'] ?></option>
                                        <?php }
                                        ?>
                                    </select>
                                    <label id="payment_type-error" class="error hide" for="payment_type">This field is required.</label>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <?php $value = (!empty($expense->amount) ? $expense->amount : ''); ?>
                                        <label for="time" class="control-label"><?php echo _l('expense_add_edit_amount'); ?><span class="text-danger"> *</span></label>
                                        <input class="form-control form-check-input" type="text" id="amount" name="amount" value="<?= $value ?>" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');">
                                    </div>
                                </div>
                            </div>

                            <div class="row food_amount_div" style="display:none;">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <?php $value = (!empty($expense->food_amount) ? $expense->food_amount : '0'); ?>
                                        <label for="time" class="control-label"><?php echo _l('expense_food_amount'); ?></label>
                                        <input class="form-control form-check-input" type="text" id="food_amount" name="food_amount" value="<?= $value ?>" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <?php $value = (!empty($expense->tax) ? $expense->tax : '0'); ?>
                                        <label for="time" class="control-label"><?php echo _l('expense_tax'); ?></label>
                                        <input class="form-control form-check-input" type="text" id="tax" name="tax" value="<?= $value ?>" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <?php $value = (!empty($expense->total_amount) ? $expense->total_amount : ''); ?>
                                        <label for="time" class="control-label"><?php echo _l('expense_total_amount'); ?><span class="text-danger"> *</span></label>
                                        <input class="form-control form-check-input" type="text" id="total_amount" name="total_amount" value="<?= $value ?>" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');">
                                    </div>
                                </div>
                            </div>
                            <div class="hotel_night_count hide">0</div>

                            <div class="row">
                                <div class="col-md-6">
                                    <?php $selected = (!empty($expense->paymentmode) ? $expense->paymentmode : ''); ?>
                                    <?php echo render_select('paymentmode', $payment_modes, ['id', 'name'], 'payment_mode', $selected); ?>
                                </div>
                                <div class="col-md-6">
                                    <?php $value = (isset($expense) ? $expense->reference_no : ''); ?>
                                    <?php echo render_input('reference_no', 'expense_add_edit_reference_no', $value); ?>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="type" class="control-label"><?php echo _l('expense_status'); ?></label>
                                        <select id="status" name="status" data-live-search="true" data-width="100%" class="selectpicker projects ajax-search">
                                            <option value=""></option>
                                            <option value="1" <?= ($expense->status == 1) ? 'selected' : '' ?>><?php echo _l('expense_approved'); ?></option>
                                            <option value="0" <?= ($expense->status == 0) ? 'selected' : '' ?>><?php echo _l('expense_not_approved'); ?></option>
                                        </select>
                                        <label id="type-error" class="error hide" for="type">This field is required.</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--<div role="tabpanel" class="tab-pane" id="tab_advanced_options">
                            <?php
/*$currency_attr = ['disabled' => true, 'data-show-subtext' => true];

$currency_attr = apply_filters_deprecated('expense_currency_disabled', [$currency_attr], '2.3.0', 'expense_currency_attributes');

foreach ($currencies as $currency) {
    if ($currency['isdefault'] == 1) {
        $currency_attr['data-base'] = $currency['id'];
    }
    if (isset($expense)) {
        if ($currency['id'] == $expense->currency) {
            $selected = $currency['id'];
        }
        if ($expense->billable == 0) {
            if ($expense->clientid) {
                $c = $this->clients_model->get_customer_default_currency($expense->clientid);
                if ($c != 0) {
                    $customer_currency = $c;
                }
            }
        }
    } else {
        if (isset($customer_id)) {
            $c = $this->clients_model->get_customer_default_currency($customer_id);
            if ($c != 0) {
                $customer_currency = $c;
            }
        }
        if ($currency['isdefault'] == 1) {
            $selected = $currency['id'];
        }
    }
}
$currency_attr = hooks()->apply_filters('expense_currency_attributes', $currency_attr);
*/?>
                            <div id="expense_currency">
                                <?/*= render_select('currency', $currencies, ['id', 'name', 'symbol'], 'expense_currency', $selected, $currency_attr); */?>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group select-placeholder">
                                        <label class="control-label"
                                            for="tax"><?/*= _l('tax_1'); */?></label>
                                        <select class="selectpicker display-block" data-width="100%" name="tax"
                                            data-none-selected-text="<?/*= _l('dropdown_non_selected_tex'); */?>">
                                            <option value="">
                                                <?/*= _l('no_tax'); */?>
                                            </option>
                                            <?php /*foreach ($taxes as $tax) {
                                                $selected = '';
                                                if (isset($expense)) {
                                                    if ($tax['id'] == $expense->tax) {
                                                        $selected = 'selected';
                                                    }
                                                } */?>
                                            <option
                                                value="<?/*= e($tax['id']); */?>"
                                                <?/*= e($selected); */?>
                                                data-percent="<?/*= e($tax['taxrate']); */?>"
                                                data-subtext="<?/*= e($tax['name']); */?>">
                                                <?/*= e($tax['taxrate']); */?>%
                                            </option>
                                            <?php
/*                                            } */?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group select-placeholder">
                                        <label class="control-label"
                                            for="tax2"><?/*= _l('tax_2'); */?></label>
                                        <select class="selectpicker display-block" data-width="100%" name="tax2"
                                            data-none-selected-text="<?/*= _l('dropdown_non_selected_tex'); */?>"
                                            <?php /*if (! isset($expense) || isset($expense) && $expense->tax == 0) {
                                                echo 'disabled';
                                            } */?>>
                                            <option value="">
                                                <?/*= _l('no_tax'); */?>
                                            </option>
                                            <?php /*foreach ($taxes as $tax) {
                                                $selected = '';
                                                if (isset($expense)) {
                                                    if ($tax['id'] == $expense->tax2) {
                                                        $selected = 'selected';
                                                    }
                                                } */?>
                                            <option
                                                value="<?/*= e($tax['id']); */?>"
                                                <?/*= e($selected); */?>
                                                data-percent="<?/*= e($tax['taxrate']); */?>"
                                                data-subtext="<?/*= e($tax['name']); */?>">
                                                <?/*= e($tax['taxrate']); */?>%
                                            </option>
                                            <?php
/*                                            } */?>
                                        </select>
                                    </div>
                                </div>
                                <?php /*if (! isset($expense)) { */?>
                                <div class="col-md-12 hide" id="tax_subtract">
                                    <div class="alert alert-info">
                                        <div class="checkbox checkbox-primary no-margin">
                                            <input type="checkbox" id="tax1_included">
                                            <label for="tax1_included">
                                                <?/*= _l('subtract_tax_total_from_amount', '<span id="tax_subtract_total" class="bold"></span>'); */?>
                                            </label>
                                        </div>
                                        <p class="tw-text-sm tw-mt-2 tw-ml-7">
                                            <?/*= _l('expense_subtract_info_text'); */?>
                                        </p>
                                    </div>
                                </div>
                                <?php /*} */?>
                            </div>
                            <div class="clearfix mtop15"></div>
                            <div class="row">
                                <div class="col-md-6">
                                    <?php /*$selected = (isset($expense) ? $expense->paymentmode : ''); */?>
                                    <?/*= render_select('paymentmode', $payment_modes, ['id', 'name'], 'payment_mode', $selected); */?>
                                </div>
                                <div class="col-md-6">
                                    <?php /*$value = (isset($expense) ? $expense->reference_no : ''); */?>
                                    <?/*= render_input('reference_no', 'expense_add_edit_reference_no', $value); */?>
                                </div>
                            </div>
                            <div class="form-group select-placeholder" <?php /*if (isset($expense) && ! empty($expense->recurring_from)) { */?>
                                data-toggle="tooltip"
                                data-title="<?/*= _l('create_recurring_from_child_error_message', [_l('expense_lowercase'), _l('expense_lowercase'), _l('expense_lowercase')]); */?>"
                                <?php /*} */?>>
                                <label for="repeat_every"
                                    class="control-label"><?/*= _l('expense_repeat_every'); */?></label>
                                <select name="repeat_every" id="repeat_every" class="selectpicker" data-width="100%"
                                    data-none-selected-text="<?/*= _l('dropdown_non_selected_tex'); */?>"
                                    <?php /*if (isset($expense) && ! empty($expense->recurring_from)) { */?>
                                    disabled <?php /*} */?>>
                                    <option value=""></option>
                                    <option value="1-week" <?php /*if (isset($expense) && $expense->repeat_every == 1 && $expense->recurring_type == 'week') {
                                        echo 'selected';
                                    } */?>><?/*= _l('week'); */?>
                                    </option>
                                    <option value="2-week" <?php /*if (isset($expense) && $expense->repeat_every == 2 && $expense->recurring_type == 'week') {
                                        echo 'selected';
                                    } */?>>2
                                        <?/*= _l('weeks'); */?>
                                    </option>
                                    <option value="1-month" <?php /*if (isset($expense) && $expense->repeat_every == 1 && $expense->recurring_type == 'month') {
                                        echo 'selected';
                                    } */?>>1
                                        <?/*= _l('month'); */?>
                                    </option>
                                    <option value="2-month" <?php /*if (isset($expense) && $expense->repeat_every == 2 && $expense->recurring_type == 'month') {
                                        echo 'selected';
                                    } */?>>2
                                        <?/*= _l('months'); */?>
                                    </option>
                                    <option value="3-month" <?php /*if (isset($expense) && $expense->repeat_every == 3 && $expense->recurring_type == 'month') {
                                        echo 'selected';
                                    } */?>>3
                                        <?/*= _l('months'); */?>
                                    </option>
                                    <option value="6-month" <?php /*if (isset($expense) && $expense->repeat_every == 6 && $expense->recurring_type == 'month') {
                                        echo 'selected';
                                    } */?>>6
                                        <?/*= _l('months'); */?>
                                    </option>
                                    <option value="1-year" <?php /*if (isset($expense) && $expense->repeat_every == 1 && $expense->recurring_type == 'year') {
                                        echo 'selected';
                                    } */?>>1
                                        <?/*= _l('year'); */?>
                                    </option>
                                    <option value="custom" <?php /*if (isset($expense) && $expense->custom_recurring == 1) {
                                        echo 'selected';
                                    } */?>><?/*= _l('recurring_custom'); */?>
                                    </option>
                                </select>
                            </div>
                            <div class="recurring_custom <?php /*if ((isset($expense) && $expense->custom_recurring != 1) || (! isset($expense))) {
                                echo 'hide';
                            } */?>">
                                <div class="row">
                                    <div class="col-md-6">
                                        <?php /*$value = (isset($expense) && $expense->custom_recurring == 1 ? $expense->repeat_every : 1); */?>
                                        <?/*= render_input('repeat_every_custom', '', $value, 'number', ['min' => 1]); */?>
                                    </div>
                                    <div class="col-md-6">
                                        <select name="repeat_type_custom" id="repeat_type_custom" class="selectpicker"
                                            data-width="100%"
                                            data-none-selected-text="<?/*= _l('dropdown_non_selected_tex'); */?>">
                                            <option value="day" <?php /*if (isset($expense) && $expense->custom_recurring == 1 && $expense->recurring_type == 'day') {
                                                echo 'selected';
                                            } */?>><?/*= _l('expense_recurring_days'); */?>
                                            </option>
                                            <option value="week" <?php /*if (isset($expense) && $expense->custom_recurring == 1 && $expense->recurring_type == 'week') {
                                                echo 'selected';
                                            } */?>><?/*= _l('expense_recurring_weeks'); */?>
                                            </option>
                                            <option value="month" <?php /*if (isset($expense) && $expense->custom_recurring == 1 && $expense->recurring_type == 'month') {
                                                echo 'selected';
                                            } */?>><?/*= _l('expense_recurring_months'); */?>
                                            </option>
                                            <option value="year" <?php /*if (isset($expense) && $expense->custom_recurring == 1 && $expense->recurring_type == 'year') {
                                                echo 'selected';
                                            } */?>><?/*= _l('expense_recurring_years'); */?>
                                            </option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div id="cycles_wrapper" class="<?php /*if (! isset($expense) || (isset($expense) && $expense->recurring == 0)) {
                                echo ' hide';
                            }*/?>">
                                <?php /*$value = (isset($expense) ? $expense->cycles : 0); */?>
                                <div class="form-group recurring-cycles">
                                    <label
                                        for="cycles"><?/*= _l('recurring_total_cycles'); */?>
                                        <?php /*if (isset($expense) && $expense->total_cycles > 0) {
                                            echo '<small>' . e(_l('cycles_passed', $expense->total_cycles)) . '</small>';
                                        }
*/?>
                                    </label>
                                    <div class="input-group">
                                        <input type="number" class="form-control" <?php /*if ($value == 0) {
                                            echo ' disabled';
                                        } */?> name="cycles" id="cycles"
                                        value="<?/*= e($value); */?>"
                                        <?php /*if (isset($expense) && $expense->total_cycles > 0) {
                                            echo 'min="' . e($expense->total_cycles) . '"';
                                        } */?>>
                                        <div class="input-group-addon">
                                            <div class="checkbox">
                                                <input type="checkbox" <?php /*if ($value == 0) {
                                                    echo ' checked';
                                                } */?> id="unlimited_cycles">
                                                <label
                                                    for="unlimited_cycles"><?/*= _l('cycles_infinity'); */?></label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <?php /*$hide_invoice_recurring_options = (isset($expense) && $expense->billable == 1) ? '' : 'hide'; */?>
                                <div
                                    class="checkbox checkbox-primary billable_recurring_options <?/*= e($hide_invoice_recurring_options); */?>">
                                    <input type="checkbox" id="create_invoice_billable" name="create_invoice_billable"
                                        <?php /*if (isset($expense)) {
                                            if ($expense->create_invoice_billable == 1) {
                                                echo 'checked';
                                            }
                                        } */?>>
                                    <label for="create_invoice_billable"><i class="fa-regular fa-circle-question"
                                            data-toggle="tooltip"
                                            title="<?/*= _l('expense_recurring_autocreate_invoice_tooltip'); */?>"></i>
                                        <?/*= _l('expense_recurring_auto_create_invoice'); */?></label>
                                </div>
                            </div>
                            <div
                                class="checkbox checkbox-primary billable_recurring_options <?/*= e($hide_invoice_recurring_options); */?>">
                                <input type="checkbox" name="send_invoice_to_customer" id="send_invoice_to_customer"
                                    <?/*= isset($expense) && $expense->send_invoice_to_customer == 1 ? 'checked' : ''; */?>>
                                <label
                                    for="send_invoice_to_customer"><?/*= _l('expense_recurring_send_custom_on_renew'); */?></label>
                            </div>
                        </div>-->
                    </div>
                    <div class="text-right">
                        <button type="submit" class="btn btn-primary">
                            <?= _l('submit'); ?>
                        </button>
                    </div>
                </div>
            </div>
            <?php hooks()->do_action('before_expense_form_template_close', $expense ?? null); ?>
            <?= form_close(); ?>
        </div>

    </div>
</div>
<?php $this->load->view('admin/expenses/expense_category'); ?>
<?php init_tail(); ?>
<script>
    var customer_currency = '';
    Dropzone.options.expenseForm = false;
    var expenseDropzone;
    init_ajax_project_search_by_customer_id();
    var selectCurrency = $('select[name="currency"]');

    <?php if (isset($customer_currency)) { ?>
    var customer_currency = '<?= e($customer_currency); ?>';
    <?php } ?>

    $(function() {
        $('body').on('change', '#project_id', function() {
            var project_id = $(this).val();
            if (project_id != '') {
                if (customer_currency != 0) {
                    selectCurrency.val(customer_currency);
                    selectCurrency.selectpicker('refresh');
                } else {
                    set_base_currency();
                }
            } else {
                do_billable_checkbox();
            }
        });

        if ($('#dropzoneDragArea').length > 0) {
            expenseDropzone = new Dropzone("#expense-form", appCreateDropzoneOptions({
                autoProcessQueue: false,
                clickable: '#dropzoneDragArea',
                previewsContainer: '.dropzone-previews',
                addRemoveLinks: true,
                maxFiles: 1,
                success: function(file, response) {
                    response = JSON.parse(response);
                    if (this.getUploadingFiles().length === 0 && this.getQueuedFiles()
                        .length ===
                        0) {
                        window.location.assign(response.url);
                    }
                },
            }));
        }

        appValidateForm($('#expense-form'), {
            category: 'required',
            date: 'required',
            amount: 'required',
            currency: 'required',
            repeat_every_custom: {
                min: 1
            },
        }, expenseSubmitHandler);

        $('input[name="billable"]').on('change', function() {
            do_billable_checkbox();
        });

        $('#repeat_every').on('change', function() {
            if ($(this).selectpicker('val') != '' && $('input[name="billable"]').prop('checked') ==
                true) {
                $('.billable_recurring_options').removeClass('hide');
            } else {
                $('.billable_recurring_options').addClass('hide');
            }
        });

        // hide invoice recurring options on page load
        $('#repeat_every').trigger('change');

        $('select[name="clientid"]').on('change', function() {
            customer_init();
            do_billable_checkbox();
            $('input[name="billable"]').trigger('change');
        });

        <?php if (! isset($expense)) { ?>
        $('select[name="tax"], select[name="tax2"]').on('change', function() {

            delay(function() {
                var $amount = $('#amount'),
                    taxDropdown1 = $('select[name="tax"]'),
                    taxDropdown2 = $('select[name="tax2"]'),
                    taxPercent1 = parseFloat(taxDropdown1.find('option[value="' + taxDropdown1
                        .val() + '"]').attr('data-percent')),
                    taxPercent2 = parseFloat(taxDropdown2.find('option[value="' + taxDropdown2
                        .val() + '"]').attr('data-percent')),
                    total = $amount.val();

                if (total == 0 || total == '') {
                    return;
                }

                if ($amount.attr('data-original-amount')) {
                    total = $amount.attr('data-original-amount');
                }

                total = parseFloat(total);

                if (taxDropdown1.val() || taxDropdown2.val()) {

                    $('#tax_subtract').removeClass('hide');

                    var totalTaxPercentExclude = taxPercent1;
                    if (taxDropdown2.val()) {
                        totalTaxPercentExclude += taxPercent2;
                    }

                    var totalExclude = accounting.toFixed(total - exclude_tax_from_amount(
                        totalTaxPercentExclude, total), app.options.decimal_places);
                    $('#tax_subtract_total').html(accounting.toFixed(totalExclude, app.options
                        .decimal_places));
                } else {
                    $('#tax_subtract').addClass('hide');
                }
                if ($('#tax1_included').prop('checked') == true) {
                    subtract_tax_amount_from_expense_total();
                }
            }, 200);
        });

        $('#amount').on('blur', function() {
            $(this).removeAttr('data-original-amount');
            if ($(this).val() == '' || $(this).val() == '') {
                $('#tax1_included').prop('checked', false);
                $('#tax_subtract').addClass('hide');
            } else {
                var tax1 = $('select[name="tax"]').val();
                var tax2 = $('select[name="tax2"]').val();
                if (tax1 || tax2) {
                    setTimeout(function() {
                        $('select[name="tax2"]').trigger('change');
                    }, 100);
                }
            }
        })

        $('#tax1_included').on('change', function() {

            var $amount = $('#amount'),
                total = parseFloat($amount.val());

            // da pokazuva total za 2 taxes  Subtract TAX total (136.36) from expense amount
            if (total == 0) {
                return;
            }

            if ($(this).prop('checked') == false) {
                $amount.val($amount.attr('data-original-amount'));
                return;
            }

            subtract_tax_amount_from_expense_total();
        });
        <?php } ?>
    });

    function subtract_tax_amount_from_expense_total() {
        var $amount = $('#amount'),
            total = parseFloat($amount.val()),
            taxDropdown1 = $('select[name="tax"]'),
            taxDropdown2 = $('select[name="tax2"]'),
            taxRate1 = parseFloat(taxDropdown1.find('option[value="' + taxDropdown1.val() + '"]').attr('data-percent')),
            taxRate2 = parseFloat(taxDropdown2.find('option[value="' + taxDropdown2.val() + '"]').attr('data-percent'));

        var totalTaxPercentExclude = taxRate1;
        if (taxRate2) {
            totalTaxPercentExclude += taxRate2;
        }

        if ($amount.attr('data-original-amount')) {
            total = parseFloat($amount.attr('data-original-amount'));
        }

        $amount.val(exclude_tax_from_amount(totalTaxPercentExclude, total));

        if ($amount.attr('data-original-amount') == undefined) {
            $amount.attr('data-original-amount', total);
        }
    }

    function customer_init() {
        var customer_id = $('select[name="clientid"]').val();
        var projectAjax = $('select[name="project_id"]');
        var clonedProjectsAjaxSearchSelect = projectAjax.html('').clone();
        var projectsWrapper = $('.projects-wrapper');
        projectAjax.selectpicker('destroy').remove();
        projectAjax = clonedProjectsAjaxSearchSelect;
        $('#project_ajax_search_wrapper').append(clonedProjectsAjaxSearchSelect);
        init_ajax_project_search_by_customer_id();
        if (!customer_id) {
            set_base_currency();
            projectsWrapper.addClass('hide');
        }
        $.get(admin_url + 'expenses/get_customer_change_data/' + customer_id, function(response) {
            if (customer_id && response.customer_has_projects) {
                projectsWrapper.removeClass('hide');
            } else {
                projectsWrapper.addClass('hide');
            }
            var client_currency = parseInt(response.client_currency);
            if (client_currency != 0) {
                customer_currency = client_currency;
                do_billable_checkbox();
            } else {
                customer_currency = '';
                set_base_currency();
            }
        }, 'json');
    }

    function expenseSubmitHandler(form) {

        selectCurrency.prop('disabled', false);

        $('select[name="tax2"]').prop('disabled', false);
        $('input[name="billable"]').prop('disabled', false);
        $('input[name="date"]').prop('disabled', false);

        $.post(form.action, $(form).serialize()).done(function(response) {
            response = JSON.parse(response);
            if (response.expenseid) {
                if (typeof(expenseDropzone) !== 'undefined') {
                    if (expenseDropzone.getQueuedFiles().length > 0) {
                        expenseDropzone.options.url = admin_url + 'expenses/add_expense_attachment/' + response
                            .expenseid;
                        expenseDropzone.processQueue();
                    } else {
                        window.location.assign(response.url);
                    }
                } else {
                    window.location.assign(response.url);
                }
            } else {
                window.location.assign(response.url);
            }
        });
        return false;
    }

    function do_billable_checkbox() {
        var val = $('select[name="clientid"]').val();
        if (val != '') {
            $('.billable').removeClass('hide');
            if ($('input[name="billable"]').prop('checked') == true) {
                if ($('#repeat_every').selectpicker('val') != '') {
                    $('.billable_recurring_options').removeClass('hide');
                } else {
                    $('.billable_recurring_options').addClass('hide');
                }
                if (customer_currency != '') {
                    selectCurrency.val(customer_currency);
                    selectCurrency.selectpicker('refresh');
                } else {
                    set_base_currency();
                }
            } else {
                $('.billable_recurring_options').addClass('hide');
                // When project is selected, the project currency will be used, either customer currency or base currency
                if ($('#project_id').selectpicker('val') == '') {
                    set_base_currency();
                }
            }
        } else {
            set_base_currency();
            $('.billable').addClass('hide');
            $('.billable_recurring_options').addClass('hide');
        }
    }

    function set_base_currency() {
        selectCurrency.val(selectCurrency.data('base'));
        selectCurrency.selectpicker('refresh');
    }
</script>
<script>
    $(document).ready(function() {
        $('#amount').on('keyup', function () {
            let type = $('#type').val();
            if(type == 'Hotel'){
                let amount = $('#amount').val();
                let tax = $('#tax').val();
                let food = $('#food_amount').val();
                let night_count = $('.hotel_night_count').text();
                let count = parseFloat(night_count) * amount;
                if(amount != '' && tax != ''){
                    let calculation = parseFloat(tax) + parseFloat(food) + parseFloat(count);
                    $('#total_amount').val(calculation);
                }
            }else{
                let amount = $('#amount').val();
                let tax = $('#tax').val();
                if(amount != '' && tax != ''){
                    let calculation = parseFloat(amount) + parseFloat(tax);
                    $('#total_amount').val(calculation);
                }
            }
        });
        $('#tax').on('keyup', function () {
            let type = $('#type').val();
            if(type == 'Hotel'){
                let amount = $('#amount').val();
                let tax = $('#tax').val();
                let food = $('#food_amount').val();
                let night_count = $('.hotel_night_count').text();
                let count = parseFloat(night_count) * amount;
                if(amount != '' && tax != ''){
                    let calculation = parseFloat(tax) + parseFloat(food) + parseFloat(count);
                    $('#total_amount').val(calculation);
                }
            }else{
                let amount = $('#amount').val();
                let tax = $('#tax').val();
                if(amount != '' && tax != ''){
                    let calculation = parseFloat(amount) + parseFloat(tax);
                    $('#total_amount').val(calculation);
                }
            }
        });
        $('#food_amount').on('keyup', function () {
            let type = $('#type').val();
            if(type == 'Hotel'){
                let amount = $('#amount').val();
                let tax = $('#tax').val();
                let food = $('#food_amount').val();
                let night_count = $('.hotel_night_count').text();
                let count = parseFloat(night_count) * amount;
                if(amount != '' && tax != ''){
                    let calculation = parseFloat(tax) + parseFloat(food) + parseFloat(count);
                    $('#total_amount').val(calculation);
                }
            }else{
                let amount = $('#amount').val();
                let tax = $('#tax').val();
                if(amount != '' && tax != ''){
                    let calculation = parseFloat(amount) + parseFloat(tax);
                    $('#total_amount').val(calculation);
                }
            }
        });

        $('#expense_billable').on('change', function () {
            if ($(this).is(':checked')) {
                $('#billable').val('1');
                $('.recepit_div').css('display','block');
            }else{
                $('#billable').val('0');
                $('.recepit_div').css('display','none');
            }
        });

        $('#category').on('change', function () {
            let category = $(this).val();
            if(category == 'Employee'){
                $('.emp_div').css('display','block');
            }else{
                $('.emp_div').css('display','none');
            }
        });

        $('#type').on('change', function () {
            let type = $(this).val();
            if(type == 'Hotel'){
                $('.date_div').css('display','block');
                $('.food_amount_div').css('display','block');
                $('.location_div').css('display','none');
            }else if(type == 'Bus' || type == 'Train' || type == 'Flight'){
                $('.location_div').css('display','block');
                $('.date_div').css('display','none');
                $('.food_amount_div').css('display','none');
            }else{
                $('.location_div').css('display','none');
                $('.date_div').css('display','none');
                $('.food_amount_div').css('display','none');
            }
        });

        $('#expense_fromdate,#expense_todate').on('change', function () {
            const startDate = new Date($("#expense_fromdate").val());
            const endDate = new Date($("#expense_todate").val());

            if (!isNaN(startDate) && !isNaN(endDate)) {
                // Calculate the difference in time
                const timeDifference = endDate - startDate;

                // Convert time difference to days
                const dayDifference = timeDifference / (1000 * 60 * 60 * 24);
                $('.hotel_night_count').html(dayDifference);
            }
        });
    });
</script>
</body>

</html>