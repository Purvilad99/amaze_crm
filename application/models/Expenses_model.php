<?php

use app\services\utilities\Arr;

defined('BASEPATH') or exit('No direct script access allowed');

class Expenses_model extends App_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Get expense(s)
     * @param  mixed $id Optional expense id
     * @return mixed     object or array
     */
    public function get_expense_data($id)
    {
//        $this->db->where('id',$id);
//        return $this->db->get(db_prefix() . 'expenses')->row();
        $this->db->where('id', $id);
        $expense = $this->db->get(db_prefix() . 'expenses')->row();
        if ($expense) {
            $expense->attachment            = '';
            $expense->filetype              = '';
            $expense->attachment_added_from = 0;

            $this->db->where('rel_id', $id);
            $this->db->where('rel_type', 'expense');
            $file = $this->db->get(db_prefix() . 'files')->row();

            if ($file) {
                $expense->attachment            = $file->file_name;
                $expense->filetype              = $file->filetype;
                $expense->attachment_added_from = $file->staffid;
            }
        }

        return $expense;
    }

    public function get($id = '', $where = [])
    {
        $this->db->select('*,' . db_prefix() . 'expenses.id as id,' . db_prefix() . 'expenses_categories.name as category_name,' . db_prefix() . 'payment_modes.name as payment_mode_name,' . db_prefix() . 'taxes.name as tax_name, ' . db_prefix() . 'taxes.taxrate as taxrate,' . db_prefix() . 'taxes_2.name as tax_name2, ' . db_prefix() . 'taxes_2.taxrate as taxrate2, ' . db_prefix() . 'expenses.id as expenseid,' . db_prefix() . 'expenses.addedfrom as addedfrom, recurring_from');
        $this->db->from(db_prefix() . 'expenses');
        $this->db->join(db_prefix() . 'clients', '' . db_prefix() . 'clients.userid = ' . db_prefix() . 'expenses.clientid', 'left');
        $this->db->join(db_prefix() . 'payment_modes', '' . db_prefix() . 'payment_modes.id = ' . db_prefix() . 'expenses.paymentmode', 'left');
        $this->db->join(db_prefix() . 'taxes', '' . db_prefix() . 'taxes.id = ' . db_prefix() . 'expenses.tax', 'left');
        $this->db->join('' . db_prefix() . 'taxes as ' . db_prefix() . 'taxes_2', '' . db_prefix() . 'taxes_2.id = ' . db_prefix() . 'expenses.tax2', 'left');
        $this->db->join(db_prefix() . 'expenses_categories', '' . db_prefix() . 'expenses_categories.id = ' . db_prefix() . 'expenses.category');
        $this->db->where($where);

        if (is_numeric($id)) {
            $this->db->where(db_prefix() . 'expenses.id', $id);
            $expense = $this->db->get()->row();
            if ($expense) {
                $expense->attachment            = '';
                $expense->filetype              = '';
                $expense->attachment_added_from = 0;

                $this->db->where('rel_id', $id);
                $this->db->where('rel_type', 'expense');
                $file = $this->db->get(db_prefix() . 'files')->row();

                if ($file) {
                    $expense->attachment            = $file->file_name;
                    $expense->filetype              = $file->filetype;
                    $expense->attachment_added_from = $file->staffid;
                }

                $this->load->model('projects_model');
                $expense->currency_data = get_currency($expense->currency);
                if ($expense->project_id) {
                    $expense->project_data = $this->projects_model->get($expense->project_id);
                }

                if (is_null($expense->payment_mode_name)) {
                    // is online payment mode
                    $this->load->model('payment_modes_model');
                    $payment_gateways = $this->payment_modes_model->get_payment_gateways(true);
                    foreach ($payment_gateways as $gateway) {
                        if ($expense->paymentmode == $gateway['id']) {
                            $expense->payment_mode_name = $gateway['name'];
                        }
                    }
                }
            }

            return $expense;
        }
        $this->db->order_by('date', 'desc');

        return $this->db->get()->result_array();
    }

    /**
     * Add new expense
     * @param mixed $data All $_POST data
     * @return  mixed
     */
    public function add($data)
    {
        $d['category'] = $data['category'];
        $d['employee'] = $data['employee'];
        $d['type'] = $data['type'];
        $d['fromdate'] = date('Y-m-d', strtotime($data['fromdate']));
        $d['todate'] = date('Y-m-d', strtotime($data['todate']));
        $d['from_location'] = $data['from_location'];
        $d['to_location'] = $data['to_location'];
//        $d['date'] = to_sql_date($data['date']);
        $d['date'] = date('Y-m-d', strtotime($data['date']));
        $d['note'] = nl2br($data['note']);
        if (isset($data['billable'])) {
            $d['billable'] = 1;
        } else {
            $d['billable'] = 0;
        }
        $d['currency'] = $data['currency'];
        $d['amount'] = $data['amount'];
        $d['food_amount'] = $data['food_amount'];
        $d['tax'] = $data['tax'];
        $d['total_amount'] = $data['total_amount'];
        $d['paymentmode'] = $data['paymentmode'];
        $d['reference_no'] = $data['reference_no'];
        $d['addedfrom'] = get_staff_user_id();
        $d['dateadded'] = date('Y-m-d H:i:s');
        $d['status'] = $data['status'];

        $this->db->insert(db_prefix() . 'expenses', $d);
        $insert_id = $this->db->insert_id();
        if ($insert_id) {
            hooks()->do_action('after_expense_added', $insert_id);

            log_activity('New Expense Added [' . $insert_id . ']');

            return $insert_id;
        }

        return false;
    }

    public function get_child_expenses($id)
    {
        $this->db->select('id');
        $this->db->where('recurring_from', $id);
        $expenses = $this->db->get(db_prefix() . 'expenses')->result_array();

        $_expenses = [];
        foreach ($expenses as $expense) {
            $_expenses[] = $this->get($expense['id']);
        }

        return $_expenses;
    }

    public function get_expenses_total($data)
    {
        $this->load->model('currencies_model');
        $base_currency     = $this->currencies_model->get_base_currency()->id;
        $base              = true;
        $currency_switcher = false;
        if (isset($data['currency'])) {
            $currencyid        = $data['currency'];
            $currency_switcher = true;
        } elseif (isset($data['customer_id']) && $data['customer_id'] != '') {
            $currencyid = $this->clients_model->get_customer_default_currency($data['customer_id']);
            if ($currencyid == 0) {
                $currencyid = $base_currency;
            } else {
                if (total_rows(db_prefix() . 'expenses', [
                    'currency' => $base_currency,
                    'clientid' => $data['customer_id'],
                ])) {
                    $currency_switcher = true;
                }
            }
        } elseif (isset($data['project_id']) && $data['project_id'] != '') {
            $this->load->model('projects_model');
            $currencyid = $this->projects_model->get_currency($data['project_id'])->id;
        } else {
            $currencyid = $base_currency;
            if (total_rows(db_prefix() . 'expenses', [
                'currency !=' => $base_currency,
            ])) {
                $currency_switcher = true;
            }
        }

        $currency = get_currency($currencyid);

        $has_permission_view = staff_can('view',  'expenses');
        $_result             = [];

        for ($i = 1; $i <= 5; $i++) {
            $this->db->select('amount,tax,tax2,invoiceid');
            $this->db->where('currency', $currencyid);

            if (isset($data['years']) && count($data['years']) > 0) {
                $this->db->where('YEAR(date) IN (' . implode(', ', array_map(function ($year) {
                    return get_instance()->db->escape_str($year);
                }, $data['years'])) . ')');
            } else {
                $this->db->where('YEAR(date) = ' . date('Y'));
            }
            if (isset($data['customer_id']) && $data['customer_id'] != '') {
                $this->db->where('clientid', $data['customer_id']);
            }
            if (isset($data['project_id']) && $data['project_id'] != '') {
                $this->db->where('project_id', $data['project_id']);
            }

            if (!$has_permission_view) {
                $this->db->where('addedfrom', get_staff_user_id());
            }
            switch ($i) {
                case 1:
                    $key = 'all';

                    break;
                case 2:
                    $key = 'billable';
                    $this->db->where('billable', 1);

                    break;
                case 3:
                    $key = 'non_billable';
                    $this->db->where('billable', 0);

                    break;
                case 4:
                    $key = 'billed';
                    $this->db->where('billable', 1);
                    $this->db->where('invoiceid IS NOT NULL');
                    $this->db->where('invoiceid IN (SELECT invoiceid FROM ' . db_prefix() . 'invoices WHERE status=2 AND id=' . db_prefix() . 'expenses.invoiceid)');

                    break;
                case 5:
                    $key = 'unbilled';
                    $this->db->where('billable', 1);
                    $this->db->where('invoiceid IS NULL');

                    break;
            }
            $all_expenses = $this->db->get(db_prefix() . 'expenses')->result_array();
            $_total_all   = [];
            $cached_taxes = [];
            foreach ($all_expenses as $expense) {
                $_total = $expense['amount'];
                if ($expense['tax'] != 0) {
                    if (!isset($cached_taxes[$expense['tax']])) {
                        $tax                           = get_tax_by_id($expense['tax']);
                        $cached_taxes[$expense['tax']] = $tax;
                    } else {
                        $tax = $cached_taxes[$expense['tax']];
                    }
                    $_total += ($_total / 100 * $tax->taxrate);
                }
                if ($expense['tax2'] != 0) {
                    if (!isset($cached_taxes[$expense['tax2']])) {
                        $tax                            = get_tax_by_id($expense['tax2']);
                        $cached_taxes[$expense['tax2']] = $tax;
                    } else {
                        $tax = $cached_taxes[$expense['tax2']];
                    }
                    $_total += ($expense['amount'] / 100 * $tax->taxrate);
                }
                array_push($_total_all, $_total);
            }
            $_result[$key]['total'] = app_format_money(array_sum($_total_all), $currency);
        }
        $_result['currency_switcher'] = $currency_switcher;
        $_result['currencyid']        = $currencyid;

        // All expenses
        /*   $this->db->select('amount,tax');
        $this->db->where('currency',$currencyid);


        $this->db->select('amount,tax,invoiceid');
        $this->db->where('currency',$currencyid);
        $this->db->where('billable',1);
        $this->db->where('invoiceid IS NOT NULL');

        $all_expenses = $this->db->get(db_prefix().'expenses')->result_array();
        $_total_all = array();
        foreach($all_expenses as $expense){
        $_total = 0;
        if(total_rows(db_prefix().'invoices',array('status'=>2,'id'=>$expense['invoiceid'])) > 0){
        $_total = $expense['amount'];
        if($expense['tax'] != 0){
        $tax = get_tax_by_id($expense['tax']);
        $_total += ($_total / 100 * $tax->taxrate);
        }
        }
        array_push($_total_all,$_total);
        }
        $_result['billed']['total'] = app_format_money(array_sum($_total_all), $currency);

        $this->db->select('amount,tax,invoiceid');
        $this->db->where('currency',$currencyid);
        $this->db->where('billable',1);
        $this->db->where('invoiceid IS NOT NULL');

        $all_expenses = $this->db->get(db_prefix().'expenses')->result_array();
        $_total_all = array();
        foreach($all_expenses as $expense){
        $_total = 0;
        if(total_rows(db_prefix().'invoices','status NOT IN(2,5) AND id ='.$expense['invoiceid']) > 0){
        echo $this->db->last_query();
        $_total = $expense['amount'];
        if($expense['tax'] != 0){
        $tax = get_tax_by_id($expense['tax']);
        $_total += ($_total / 100 * $tax->taxrate);
        }
        }
        array_push($_total_all,$_total);
        }
        $_result['unbilled']['total'] = app_format_money(array_sum($_total_all), $currency);*/

        return $_result;
    }

    /**
     * Update expense
     * @param  mixed $data All $_POST data
     * @param  mixed $id   expense id to update
     * @return boolean
     */
    public function update($data, $id)
    {
        $d['category'] = $data['category'];
        $d['employee'] = $data['employee'];
        $d['type'] = $data['type'];
        $d['fromdate'] = date('Y-m-d', strtotime($data['fromdate']));
        $d['todate'] = date('Y-m-d', strtotime($data['todate']));
        $d['from_location'] = $data['from_location'];
        $d['to_location'] = $data['to_location'];
        $d['date'] = date('Y-m-d', strtotime($data['date']));
        $d['note'] = nl2br($data['note']);
        if (isset($data['billable'])) {
            $d['billable'] = 1;
        } else {
            $d['billable'] = 0;
        }
        $d['currency'] = $data['currency'];
        $d['amount'] = $data['amount'];
        $d['food_amount'] = $data['food_amount'];
        $d['tax'] = $data['tax'];
        $d['total_amount'] = $data['total_amount'];
        $d['paymentmode'] = $data['paymentmode'];
        $d['reference_no'] = $data['reference_no'];
        $d['addedfrom'] = get_staff_user_id();
        $d['dateadded'] = date('Y-m-d H:i:s');
        $d['status'] = $data['status'];

        $this->db->where('id', $id);
        $this->db->update(db_prefix() . 'expenses', $d);

        if ($this->db->affected_rows() > 0) {
            $updated = true;
        }

        do_action_deprecated('after_expense_updated', [$id], '2.9.4', 'expense_updated');

        hooks()->do_action('expense_updated', [
            'id'            => $id,
            'data'          => $data,
            'updated'       => &$updated,
        ]);

        if ($updated) {
            log_activity('Expense Updated [' . $id . ']');
        }

        return $updated;
    }

    /**
     * @param  integer ID
     * @return mixed
     * Delete expense from database, if used return
     */
    public function delete($id, $simpleDelete = false)
    {

        $this->db->where('id', $id);
        $this->db->delete(db_prefix() . 'expenses');

        if ($this->db->affected_rows() > 0) {
            // Delete the custom field values
//            $this->db->where('relid', $id);
//            $this->db->where('fieldto', 'expenses');
//            $this->db->delete(db_prefix() . 'customfieldsvalues');
            // Get related tasks
//            $this->db->where('rel_type', 'expense');
//            $this->db->where('rel_id', $id);
//            $tasks = $this->db->get(db_prefix() . 'tasks')->result_array();
//            foreach ($tasks as $task) {
//                $this->tasks_model->delete_task($task['id']);
//            }
//
//            $this->delete_expense_attachment($id);
//
//            $this->db->where('recurring_from', $id);
//            $this->db->update(db_prefix() . 'expenses', ['recurring_from' => null]);
//
//            $this->db->where('rel_type', 'expense');
//            $this->db->where('rel_id', $id);
//            $this->db->delete(db_prefix() . 'reminders');
//
//            $this->db->where('rel_id', $id);
//            $this->db->where('rel_type', 'expense');
//            $this->db->delete(db_prefix() . 'related_items');

            log_activity('Expense Deleted [' . $id . ']');

            hooks()->do_action('after_expense_deleted', $id);

            return true;
        }

        return false;
    }

    /**
     * Convert expense to invoice
     * @param  mixed  $id   expense id
     * @return mixed
     */
    public function convert_to_invoice($id, $draft_invoice = false, $params = [])
    {
        $expense          = $this->get($id);
        $new_invoice_data = [];
        $client           = $this->clients_model->get($expense->clientid);

        if ($draft_invoice == true) {
            $new_invoice_data['save_as_draft'] = true;
        }
        $new_invoice_data['clientid'] = $expense->clientid;
        $new_invoice_data['number']   = get_option('next_invoice_number');
        $invoice_date                 = (isset($params['invoice_date']) ? $params['invoice_date'] : date('Y-m-d'));
        $new_invoice_data['date']     = _d($invoice_date);

        if (get_option('invoice_due_after') != 0) {
            $new_invoice_data['duedate'] = _d(date('Y-m-d', strtotime('+' . get_option('invoice_due_after') . ' DAY', strtotime($invoice_date))));
        }

        $new_invoice_data['show_quantity_as'] = 1;
        $new_invoice_data['terms']            = get_option('predefined_terms_invoice');
        $new_invoice_data['clientnote']       = get_option('predefined_clientnote_invoice');
        $new_invoice_data['discount_total']   = 0;
        $new_invoice_data['sale_agent']       = 0;
        $new_invoice_data['adjustment']       = 0;
        $new_invoice_data['project_id']       = $expense->project_id;

        $new_invoice_data['subtotal'] = $expense->amount;
        $total                        = $expense->amount;

        if ($expense->tax != 0) {
            $total += ($expense->amount / 100 * $expense->taxrate);
        }
        if ($expense->tax2 != 0) {
            $total += ($expense->amount / 100 * $expense->taxrate2);
        }

        $new_invoice_data['total']     = $total;
        $new_invoice_data['currency']  = $expense->currency;
        $new_invoice_data['status']    = 1;
        $new_invoice_data['adminnote'] = '';
        // Since version 1.0.6
        $new_invoice_data['billing_street']  = clear_textarea_breaks($client->billing_street);
        $new_invoice_data['billing_city']    = $client->billing_city;
        $new_invoice_data['billing_state']   = $client->billing_state;
        $new_invoice_data['billing_zip']     = $client->billing_zip;
        $new_invoice_data['billing_country'] = $client->billing_country;
        if (!empty($client->shipping_street)) {
            $new_invoice_data['shipping_street']          = clear_textarea_breaks($client->shipping_street);
            $new_invoice_data['shipping_city']            = $client->shipping_city;
            $new_invoice_data['shipping_state']           = $client->shipping_state;
            $new_invoice_data['shipping_zip']             = $client->shipping_zip;
            $new_invoice_data['shipping_country']         = $client->shipping_country;
            $new_invoice_data['include_shipping']         = 1;
            $new_invoice_data['show_shipping_on_invoice'] = 1;
        } else {
            $new_invoice_data['include_shipping']         = 0;
            $new_invoice_data['show_shipping_on_invoice'] = 1;
        }

        $this->load->model('payment_modes_model');
        $modes = $this->payment_modes_model->get('', [
            'expenses_only !=' => 1,
        ]);
        $temp_modes = [];
        foreach ($modes as $mode) {
            if ($mode['selected_by_default'] == 0) {
                continue;
            }
            $temp_modes[] = $mode['id'];
        }

        $new_invoice_data['billed_expenses'][1] = [
            $expense->expenseid,
        ];
        $new_invoice_data['allowed_payment_modes']           = $temp_modes;
        $new_invoice_data['newitems'][1]['description']      = _l('item_as_expense') . ' ' . $expense->name;
        $new_invoice_data['newitems'][1]['long_description'] = $expense->description;

        if (isset($params['include_note']) && $params['include_note'] == true && !empty($expense->note)) {
            $new_invoice_data['newitems'][1]['long_description'] .= PHP_EOL . $expense->note;
        }
        if (isset($params['include_name']) && $params['include_name'] == true && !empty($expense->expense_name)) {
            $new_invoice_data['newitems'][1]['long_description'] .= PHP_EOL . $expense->expense_name;
        }

        $new_invoice_data['newitems'][1]['unit']    = '';
        $new_invoice_data['newitems'][1]['qty']     = 1;
        $new_invoice_data['newitems'][1]['taxname'] = [];
        if ($expense->tax != 0) {
            $tax_data = get_tax_by_id($expense->tax);
            array_push($new_invoice_data['newitems'][1]['taxname'], $tax_data->name . '|' . $tax_data->taxrate);
        }
        if ($expense->tax2 != 0) {
            $tax_data = get_tax_by_id($expense->tax2);
            array_push($new_invoice_data['newitems'][1]['taxname'], $tax_data->name . '|' . $tax_data->taxrate);
        }

        $new_invoice_data['newitems'][1]['rate']  = $expense->amount;
        $new_invoice_data['newitems'][1]['order'] = 1;
        $this->load->model('invoices_model');

        $invoiceid = $this->invoices_model->add($new_invoice_data, true);
        if ($invoiceid) {
            $this->db->where('id', $expense->expenseid);
            $this->db->update(db_prefix() . 'expenses', [
                'invoiceid' => $invoiceid,
            ]);

            if (is_custom_fields_smart_transfer_enabled()) {
                $this->db->where('fieldto', 'expenses');
                $this->db->where('active', 1);
                $cfExpenses = $this->db->get(db_prefix() . 'customfields')->result_array();
                foreach ($cfExpenses as $field) {
                    $tmpSlug = explode('_', $field['slug'], 2);
                    if (isset($tmpSlug[1])) {
                        $this->db->where('fieldto', 'invoice');
                        $this->db->group_start();
                        $this->db->like('slug', 'invoice_' . $tmpSlug[1], 'after');
                        $this->db->where('type', $field['type']);
                        $this->db->where('options', $field['options']);
                        $this->db->where('active', 1);
                        $this->db->group_end();

                        $cfTransfer = $this->db->get(db_prefix() . 'customfields')->result_array();

                        // Don't make mistakes
                        // Only valid if 1 result returned
                        // + if field names similarity is equal or more then CUSTOM_FIELD_TRANSFER_SIMILARITY%
                        if (count($cfTransfer) == 1 && ((similarity($field['name'], $cfTransfer[0]['name']) * 100) >= CUSTOM_FIELD_TRANSFER_SIMILARITY)) {
                            $value = get_custom_field_value($id, $field['id'], 'expenses', false);
                            if ($value == '') {
                                continue;
                            }
                            $this->db->insert(db_prefix() . 'customfieldsvalues', [
                                'relid'   => $invoiceid,
                                'fieldid' => $cfTransfer[0]['id'],
                                'fieldto' => 'invoice',
                                'value'   => $value,
                            ]);
                        }
                    }
                }
            }

            log_activity('Expense Converted To Invoice [ExpenseID: ' . $expense->expenseid . ', InvoiceID: ' . $invoiceid . ']');

            hooks()->do_action('expense_converted_to_invoice', ['expense_id' => $expense->expenseid, 'invoice_id' => $invoiceid]);

            return $invoiceid;
        }

        return false;
    }

    /**
     * Copy expense
     * @param  mixed $id expense id to copy from
     * @return mixed
     */
    public function copy($id)
    {
        $expense_fields   = $this->db->list_fields(db_prefix() . 'expenses');
        $expense          = $this->get($id);
        $new_expense_data = [];
        foreach ($expense_fields as $field) {
            if (isset($expense->$field)) {
                // We dont need these fields.
                if ($field != 'invoiceid' && $field != 'id' && $field != 'recurring_from') {
                    $new_expense_data[$field] = $expense->$field;
                }
            }
        }
        $new_expense_data['addedfrom']           = get_staff_user_id();
        $new_expense_data['dateadded']           = date('Y-m-d H:i:s');
        $new_expense_data['last_recurring_date'] = null;
        $new_expense_data['total_cycles']        = 0;

        $this->db->insert(db_prefix() . 'expenses', $new_expense_data);
        $insert_id = $this->db->insert_id();
        if ($insert_id) {
            // Get the old expense custom field and add to the new
            $custom_fields = get_custom_fields('expenses');
            foreach ($custom_fields as $field) {
                $value = get_custom_field_value($id, $field['id'], 'expenses', false);
                if ($value == '') {
                    continue;
                }
                $this->db->insert(db_prefix() . 'customfieldsvalues', [
                    'relid'   => $insert_id,
                    'fieldid' => $field['id'],
                    'fieldto' => 'expenses',
                    'value'   => $value,
                ]);
            }
            log_activity('Expense Copied [ExpenseID' . $id . ', NewExpenseID: ' . $insert_id . ']');

            return $insert_id;
        }

        return false;
    }

    /**
     * Delete Expense attachment
     * @param  mixed $id expense id
     * @return boolean
     */
    public function delete_expense_attachment($id)
    {
        if (is_dir(get_upload_path_by_type('expense') . $id)) {
            if (delete_dir(get_upload_path_by_type('expense') . $id)) {
                $this->db->where('rel_id', $id);
                $this->db->where('rel_type', 'expense');
                $this->db->delete(db_prefix() . 'files');
                log_activity('Expense Receipt Deleted [ExpenseID: ' . $id . ']');

                return true;
            }
        }

        return false;
    }

    /* Categories start */

    /**
     * Get expense category
     * @param  mixed $id category id (Optional)
     * @return mixed     object or array
     */
    public function get_category($id = '')
    {
        if (is_numeric($id)) {
            $this->db->where('id', $id);

            return $this->db->get(db_prefix() . 'expenses_categories')->row();
        }
        $this->db->order_by('name', 'asc');

        return $this->db->get(db_prefix() . 'expenses_categories')->result_array();
    }

    /**
     * Add new expense category
     * @param mixed $data All $_POST data
     * @return boolean
     */
    public function add_category($data)
    {
        $data['description'] = nl2br($data['description']);
        $this->db->insert(db_prefix() . 'expenses_categories', $data);
        $insert_id = $this->db->insert_id();
        if ($insert_id) {
            log_activity('New Expense Category Added [ID: ' . $insert_id . ']');

            return $insert_id;
        }

        return false;
    }

    /**
     * Update expense category
     * @param  mixed $data All $_POST data
     * @param  mixed $id   expense id to update
     * @return boolean
     */
    public function update_category($data, $id)
    {
        $data['description'] = nl2br($data['description']);
        $this->db->where('id', $id);
        $this->db->update(db_prefix() . 'expenses_categories', $data);
        if ($this->db->affected_rows() > 0) {
            log_activity('Expense Category Updated [ID: ' . $id . ']');

            return true;
        }

        return false;
    }

    /**
     * @param  integer ID
     * @return mixed
     * Delete expense category from database, if used return array with key referenced
     */
    public function delete_category($id)
    {
        if (is_reference_in_table('category', db_prefix() . 'expenses', $id)) {
            return [
                'referenced' => true,
            ];
        }
        $this->db->where('id', $id);
        $this->db->delete(db_prefix() . 'expenses_categories');
        if ($this->db->affected_rows() > 0) {
            log_activity('Expense Category Deleted [' . $id . ']');

            return true;
        }

        return false;
    }

    public function get_expenses_years()
    {
        return $this->db->query('SELECT DISTINCT(YEAR(date)) as year FROM ' . db_prefix() . 'expenses ORDER by year DESC')->result_array();
    }

    public function update_date($id,$formArray)
    {
        $this->db->where('id',$id);
        $this->db->update(db_prefix() . 'expenses',$formArray);
        return true;
    }

    public function fetch_report_data($data){
        if (isset($data['employee']) && !empty($data['employee'])) {
            $this->db->where('tblexpenses.employee', $data['employee']);
        }
        if (isset($data['start_date']) && !empty($data['start_date'])) {
            $start_date = date('Y-m-d', strtotime($data['start_date']));
            $this->db->where('tblexpenses.date >=', $start_date);
        }
        if (isset($data['end_date']) && !empty($data['end_date'])) {
            $end_date = date('Y-m-d', strtotime($data['end_date'] . ' 23:59:59'));
            $this->db->where('tblexpenses.date <=', $end_date);
        }
        $this->db->select(['tblexpenses.*']);
        $data = $this->db->get('tblexpenses')->result_array();
//        echo $this->db->last_query(); exit;
        return $data;
    }

    public function update_approve_status($id,$formArray)
    {
        $this->db->where('id',$id);
        $this->db->update(db_prefix() . 'expenses',$formArray);
        return true;
    }

    public function get_employee($id = '')
    {
        if (is_numeric($id)) {
            $this->db->where('staffid', $id);

            return $this->db->get(db_prefix() . 'staff')->row();
        }
        $this->db->order_by('firstname', 'asc');

        return $this->db->get(db_prefix() . 'staff')->result_array();
    }
}
