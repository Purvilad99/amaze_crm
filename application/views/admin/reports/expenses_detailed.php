<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="tw-flex tw-justify-between tw-items-center tw-mb-2 sm:tw-mb-4">
                    <div class="tw-flex tw-items-center">
                        <h4 class="tw-my-0 tw-font-semibold tw-text-lg tw-text-neutral-700 tw-mr-4">
                            <?php echo _l('expenses_report'); ?>
                        </h4>
                        <?php
                        $_currency = $base_currency;
                        if (is_using_multiple_currencies(db_prefix() . 'expenses')) { ?>
                            <div data-toggle="tooltip"
                                 title="<?php echo _l('report_expenses_base_currency_select_explanation'); ?>"
                                 class="-tw-mt-1.5">
                                <select class="selectpicker" name="currencies"
                                        data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">
                                    <?php foreach ($currencies as $c) {
                                        $selected = '';
                                        if (!$this->input->get('currency')) {
                                            if ($c['id'] == $base_currency->id) {
                                                $selected  = 'selected';
                                                $_currency = $base_currency;
                                            }
                                        } else {
                                            if ($this->input->get('currency') == $c['id']) {
                                                $selected  = 'selected';
                                                $_currency = $this->currencies_model->get($c['id']);
                                            }
                                        } ?>
                                        <option value="<?php echo $c['id']; ?>" <?php echo $selected; ?>>
                                            <?php echo $c['name']; ?>
                                        </option>
                                        <?php
                                    } ?>
                                </select>
                            </div>
                        <?php } ?>
                    </div>

                    <div class="tw-space-x-3 tw-flex tw-items-center">
                        <a href="<?php echo admin_url('reports/expenses'); ?>" class="btn btn-default">
                            <?php echo _l('go_back'); ?>
                        </a>

                        <div id="vueApp" class="tw-inline pull-right tw-ml-0 sm:tw-ml-1.5">
                            <app-filters
                                    id="<?php echo $table->id(); ?>"
                                    view="<?php echo $table->viewName(); ?>"
                                    :saved-filters="<?php echo $table->filtersJs(); ?>"
                                    :available-rules="<?php echo $table->rulesJs(); ?>">
                            </app-filters>
                        </div>
                    </div>
                </div>
                <div class="panel_s">
                    <div class="panel-body">
                        <div class="panel-table-full">
                            <!--<table class="table dt-table-loading table-expenses" id="expenses_detailed_report">
                                <thead>
                                    <tr>
                                        <th><?php /*echo _l('the_number_sign'); */?></th>
                                        <th><?php /*echo _l('expense_category'); */?></th>
                                        <th><?php /*echo _l('expense_total_amount'); */?></th>
                                        <th><?php /*echo _l('expense_employee'); */?></th>
                                        <th><?php /*echo _l('expense_type'); */?></th>
                                        <th><?php /*echo _l('expense_dt_table_heading_date'); */?></th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                                <tfoot>
                                    <tr>
                                        <td></td>
                                        <td class="subtotal"></td>
                                        <td></td>
                                        <td class="tax_1"></td>
                                        <td class="tax_2"></td>
                                        <td class="total_tax"></td>
                                        <td class="total"></td>
                                    </tr>
                                </tfoot>
                            </table>-->
                            <table class="table dataTable no-footer dt-table table-expenses" id="expenses_detailed_report">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Category</th>
                                    <th>Employee</th>
                                    <th>Type</th>
                                    <th>Date</th>
                                    <th>Billable</th>
                                    <th>Total Amount</th>
                                    <th>Payment Mode</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                if(!empty($expenses_data)){
                                    $i = 1;
                                    foreach ($expenses_data as $val){ ?>
                                        <tr>
                                            <td><?= $i ?></td>
                                            <td><?= $val->category; ?></td>
                                            <td>
                                                <?php if(!empty($val->employee)){
                                                    $emp = $this->db->where_in('staffid', $val->employee)->get('tblstaff')->row_array();
                                                    echo $emp['firstname'].' '.$emp['lastname'];
                                                } ?>
                                            </td>
                                            <td>
                                                <?= $val->type ?>
                                            </td>
                                            <td><?= date('Y-m-d',strtotime($val->date)); ?></td>
                                            <td><?php if($val->billable == '0'){ ?>
                                                    No
                                                <?php }else{ ?>
                                                    Yes
                                                <?php } ?>
                                            </td>
                                            <td><?= $val->total_amount ?></td>
                                            <td>
                                                <?php $paymode = $this->db->where_in('id', $val->paymentmode)->get('tblpayment_modes')->row_array();
                                                echo $paymode['name'];
                                                ?>
                                            </td>
                                        </tr>
                                        <?php $i++; ?>
                                    <?php }
                                }
                                ?>
                                </tbody>
                                <tfoot>
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td class="total_amount"></td>
                                    <td></td>
                                </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php init_tail(); ?>

<script>
    $(function() {
        // var Expenses_ServerParams = {};
        // Expenses_ServerParams['currency'] = '[name="currencies"]';

        initDataTable('.table-expenses', window.location.href, 'undefined', 'undefined', [5,
            'desc'
        ]);

        $('#expenses_detailed_report').DataTable().on('draw.dt', function() {
            console.log('Table redrawn!');
        });
        $('.table-expenses').on('draw.dt', function() {
            var expenseReportsTable = $(this).DataTable();
            console.log(expenseReportsTable);
            var sums = expenseReportsTable.ajax.json().sums;
            console.log(sums);
            /*  $(this).find('tfoot').addClass('bold');
              $(this).find('tfoot td').eq(0).html("<?php echo _l('expenses_report_total'); ?>");
        $(this).find('tfoot td.tax_1').html(sums.tax_1);
        $(this).find('tfoot td.tax_2').html(sums.tax_2);
        $(this).find('tfoot td.subtotal').html(sums.amount);
        $(this).find('tfoot td.total_tax').html(sums.total_tax);*/
            // $(this).find('tfoot td.total_amount').html(sums.amount_with_tax);
        });

        $('select[name="currencies"]').on('change', function() {
            $('.table-expenses').DataTable().ajax.reload();
        });
    });
</script>
</body>

</html>