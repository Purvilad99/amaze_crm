<?php

defined('BASEPATH') or exit('No direct script access allowed');

$this->ci->load->model('expenses_model');

return App_table::find('expenses')
    ->outputUsing(function ($params) {
        extract($params);

        $aColumns = [
            '1', // bulk actions
            db_prefix() . 'expenses.id as id',
            'category',
            'total_amount',
            'employee',
            'type',
            'date',
            'paymentmode',
            'status',
        ];

        $join = [
//            'LEFT JOIN ' . db_prefix() . 'clients ON ' . db_prefix() . 'clients.userid = ' . db_prefix() . 'expenses.clientid',
            'LEFT JOIN ' . db_prefix() . 'staff ON ' . db_prefix() . 'staff.staffid = ' . db_prefix() . 'expenses.employee',
//            'JOIN ' . db_prefix() . 'expenses_categories ON ' . db_prefix() . 'expenses_categories.id = ' . db_prefix() . 'expenses.category',
//            'LEFT JOIN ' . db_prefix() . 'projects ON ' . db_prefix() . 'projects.id = ' . db_prefix() . 'expenses.project_id',
            'LEFT JOIN ' . db_prefix() . 'files ON ' . db_prefix() . 'files.rel_id = ' . db_prefix() . 'expenses.id AND rel_type="expense"',
            'LEFT JOIN ' . db_prefix() . 'currencies ON ' . db_prefix() . 'currencies.id = ' . db_prefix() . 'expenses.currency',
        ];

        $custom_fields = get_table_custom_fields('expenses');

        foreach ($custom_fields as $key => $field) {
            $selectAs = (is_cf_date($field) ? 'date_picker_cvalue_' . $key : 'cvalue_' . $key);
            array_push($customFieldsColumns, $selectAs);
            array_push($aColumns, 'ctable_' . $key . '.value as ' . $selectAs);
            array_push($join, 'LEFT JOIN ' . db_prefix() . 'customfieldsvalues as ctable_' . $key . ' ON ' . db_prefix() . 'expenses.id = ctable_' . $key . '.relid AND ctable_' . $key . '.fieldto="' . $field['fieldto'] . '" AND ctable_' . $key . '.fieldid=' . $field['id']);
        }

        $where  = [];

        if ($filtersWhere = $this->getWhereFromRules()) {
            $where[] = $filtersWhere;
        }

        /*if ($clientid != '') {
            array_push($where, 'AND ' . db_prefix() . 'expenses.clientid=' . $this->ci->db->escape_str($clientid));
        }*/

        if (staff_cant('view', 'expenses')) {
            array_push($where, 'AND ' . db_prefix() . 'expenses.addedfrom=' . get_staff_user_id());
        }

        $sIndexColumn = 'id';
        $sTable       = db_prefix() . 'expenses';

        $aColumns = hooks()->apply_filters('expenses_table_sql_columns', $aColumns);

        // Fix for big queries. Some hosting have max_join_limit
        if (count($custom_fields) > 4) {
            @$this->ci->db->query('SET SQL_BIG_SELECTS=1');
        }

        $result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, [
            'billable',
            db_prefix() . 'currencies.symbol as currency_symbol',
//            db_prefix() . 'expenses.clientid',
            db_prefix() . 'expenses.employee',
            'tax',
//            'tax2',
//            'project_id',
//            'recurring',
        ]);
        $output  = $result['output'];
        $rResult = $result['rResult'];

        $this->ci->load->model('payment_modes_model');
//        echo "<pre>"; print_r($rResult); exit;

        foreach ($rResult as $aRow) {
            $row = [];

            if($aRow['status'] == 0){
                $row[] = '<div class="checkbox"><input type="checkbox" id="expense_approve" name="approve[]" value="' . $aRow['id'] . '"><label></label></div>';
            }else{
                $row[] = '';
            }

            $row[] = $aRow['id'];

            $categoryOutput = '';

            $categoryOutput .=  $aRow['category'] ;

            $categoryOutput .= '<div class="row-options">';

            if (staff_can('edit',  'expenses')) {
                $categoryOutput .= ' <a href="' . admin_url('expenses/expense/' . $aRow['id']) . '">' . _l('edit') . '</a>';
            }

            if (staff_can('delete',  'expenses')) {
                $categoryOutput .= ' | <a href="' . admin_url('expenses/delete/' . $aRow['id']) . '" class="text-danger _delete">' . _l('delete') . '</a>';
            }

            $categoryOutput .= '</div>';
            $row[] = $categoryOutput;

            $row[]    = $aRow['currency_symbol'].' '.$aRow['total_amount'];

            $employeeOutput = '';

            if ($aRow['employee'] != '0' && !empty($aRow['employee'])) {
                $payment_mode = $this->ci->staff_model->get($aRow['employee']);
                if ($payment_mode) {
                    $employeeOutput = $payment_mode->firstname.' '.$payment_mode->lastname;
                }
            }

            $row[] = $employeeOutput;

//            $row[] = $aRow['firstname'].' '.$aRow['lastname'];

//            $staff = $CI->db->where('staffid', $aRow['employee'])->get(db_prefix() . 'staff')->row_array();

            $row[] = $aRow['type'];

            $row[] = _d($aRow['date']);

            $paymentModeOutput = '';

            if ($aRow['paymentmode'] != '0' && !empty($aRow['paymentmode'])) {
                $payment_mode = $this->ci->payment_modes_model->get($aRow['paymentmode'], [], false, true);
                if ($payment_mode) {
                    $paymentModeOutput = $payment_mode->name;
                }
            }

            $row[] = $paymentModeOutput;

            $statusOutput = '';
            if ($aRow['status'] == 1) {
                $statusOutput .= '<span class="label label-success s-status invoice-status-2"> ' . _l('expense_approved') . '</span>';
            }else{
                $statusOutput .= '<span class="label label-danger s-status invoice-status-2"> ' . _l('expense_not_approved') . '</span>';
            }

            $row[] = $statusOutput;

            // Custom fields add values
            foreach ($customFieldsColumns as $customFieldColumn) {
                $row[] = (strpos($customFieldColumn, 'date_picker_') !== false ? _d($aRow[$customFieldColumn]) : $aRow[$customFieldColumn]);
            }

            $row['DT_RowClass'] = 'has-row-options';

            $row = hooks()->apply_filters('expenses_table_row_data', $row, $aRow);

            $output['aaData'][] = $row;
        }
        return $output;
    })->setRules([
        App_table_filter::new('employee', 'MultiSelectRule')
            ->label(_l('expense_employee'))
            ->options(function ($ci) {
                return collect($ci->expenses_model->get_employee())->map(fn ($employee) => [
                    'value' => $employee['staffid'],
                    'label' => $employee['firstname'].' '.$employee['lastname'],
                ])->all();
            }),
//        App_table_filter::new('expense_name', 'TextRule')->label(_l('expense_name')),
        App_table_filter::new('date', 'DateRule')->label(str_replace(':', '', _l('expense_date'))),
        App_table_filter::new('year', 'MultiSelectRule')
            ->label(_l('year'))
            ->raw(function ($value, $operator) {
                if ($operator == 'in') {
                    return "YEAR(date) IN (" . implode(',', $value) . ")";
                } else {
                    return "YEAR(date) NOT IN (" . implode(',', $value) . ")";
                }
            })
            ->options(function ($ci) {
                return collect($ci->expenses_model->get_expenses_years())->map(fn ($data) => [
                    'value' => $data['year'],
                    'label' => $data['year'],
                ])->all();
            }),
        App_table_filter::new('category', 'TextRule')->label(str_replace(':', '', _l('expense_category'))),
        App_table_filter::new('type', 'TextRule')->label(str_replace(':', '', _l('expense_type'))),
        App_table_filter::new('total_amount', 'NumberRule')->label(str_replace(':', '', _l('expense_total_amount'))),
        App_table_filter::new('contract_type', 'MultiSelectRule')
            ->label(_l('expense_report_category'))
            ->options(function ($ci) {
                return collect($ci->expenses_model->get_category())->map(fn ($category) => [
                    'value' => $category['id'],
                    'label' => $category['name'],
                ])->all();
            }),
        /*App_table_filter::new('billable', 'BooleanRule')->label(_l('expenses_list_billable')),
        App_table_filter::new('unbilled', 'BooleanRule')->label(_l('expenses_list_unbilled'))->raw(function ($value) {
            return $value == "1" ? 'invoiceid IS NULL' : 'invoiceid IS NOT NULL';
        }),
        App_table_filter::new('recurring', 'BooleanRule')->label(_l('expenses_list_recurring')),*/
        App_table_filter::new('paymentmode', 'SelectRule')->label(_l('payment_mode'))->options(function ($ci) {
            return collect($ci->payment_modes_model->get('', [
                'invoices_only !=' => 1,
            ], true))->map(fn ($mode) => [
                'value' => $mode['id'],
                'label' => $mode['name'],
            ])->all();
        }),
    ]);
