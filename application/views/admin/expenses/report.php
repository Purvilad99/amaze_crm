<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <h4 class="tw-mt-0 tw-font-semibold tw-text-lg tw-text-neutral-700 tw-flex tw-items-center tw-space-x-2">
                        <span>
                            <?= _l('expense_report_page'); ?>
                        </span>
                </h4>
            </div>
            <div class="panel-body">
                <form method="post" id="report_filter">
                    <div class="row">
                        <div class="col-md-3">
                            <?php
                            $staff = $this->db->get('tblstaff')->result_array();
                            echo render_select('employee', $staff, ['staffid', ['firstname', 'lastname']], 'expense_employee', $selected);
                            ?>
                        </div>
                        <div class="col-md-3">
                            <label for="time" class="control-label"><?php echo _l('expense_fromdate'); ?></label>
                            <input class="form-control form-check-input" type="date" id="expense_fromdate" name="fromdate">
                        </div>
                        <div class="col-md-3">
                            <label for="time" class="control-label"><?php echo _l('expense_todate'); ?></label>
                            <input class="form-control form-check-input" type="date" id="expense_todate" name="todate">
                        </div>
                        <div class="col-md-3" style="margin-top: 22px;">
                            <button type="submit" class="btn-tr btn btn-primary"><?php echo _l('submit'); ?></button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="content show_table_data" style="display: none;">
        <div class="row">
            <div class="col-md-12" id="small-table">
                <div class="panel_s">
                    <div class="panel-body">
                        <div class="clearfix"></div>
                        <table class="table dataTable no-footer dt-table dt-inline">
                            <thead>
                            <tr>
                                <th>Date</th>
                                <th>Type</th>
                                <th>Billable</th>
                                <th>Amount</th>
                                <th>Tax</th>
                                <th>Total Amount</th>
                                <th>Payment Mode</th>
                            </tr>
                            </thead>
                            <tbody class="show_row_data">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php init_tail(); ?>
<script>
    $('#report_filter').submit(function(e) {
        e.preventDefault();
        var filterData = {
            "employee": $('#employee').val(),
            "start_date": $('#expense_fromdate').val(),
            "end_date": $('#expense_todate').val(),
        };
        $.ajax({
            type: "post",
            url: "<?php echo base_url('admin/expenses/show_all_report_filter_data'); ?>",
            data: filterData,
            dataType: 'json',
            success: function(data) {
                $('.show_table_data').css('display','block');
                for (var i=0; i < data.length; i++) {
                    if(data[i].billable == 1){
                        var billable = 'Yes';
                    }else{
                        var billable = 'No';
                    }
                    var html = '<tr>' +
                                    '<td>'+ data[i].date +'</td>'+
                                    '<td>'+ data[i].type +'</td>'+
                                    '<td>'+ billable +'</td>'+
                                    '<td>'+ data[i].amount +'</td>'+
                                    '<td>'+ data[i].tax +'</td>'+
                                    '<td>'+ data[i].total_amount +'</td>'+
                                    '<td>'+ data[i].paymentmode +'</td>'+
                               '</tr>';
                }
                $('.show_row_data').html(html);
            }
        });
    });
</script>
