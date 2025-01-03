<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <?php
            echo form_open($this->uri->uri_string(), ['id' => 'visit_planner-form', 'class' => '_bookingunfix_transaction_form booking-unfix-form','enctype' => 'multipart/form-data']);
            /*if (isset($booking_unfix)) {
                echo form_hidden('isedit');
            }*/
            ?>
            <div class="col-md-12">
                <h4 class="tw-mt-0 tw-font-semibold tw-text-lg tw-text-neutral-700 tw-flex tw-items-center tw-space-x-2">
                <span>
                    <?= !empty($visit_planner) ? _l('edit_new_visit_planner') : _l('create_new_visit_planner'); ?>
                   </span>
                </h4>
                <?php $this->load->view('admin/visit_planner/visit_planner_template'); ?>
            </div>
            <?php echo form_close(); ?>
<!--            --><?php //$this->load->view('admin/invoice_items/item'); ?>
        </div>
    </div>
</div>
<?php init_tail(); ?>
<style>
    .error {
        color: red;
    }
</style>

</body>
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
    //$(document).on('change','#clientid', function() {
    //
    //    var clientid = $(this).val();
    //    console.log("client_id",clientid);
    //    $.ajax({
    //        url: '<?php //echo admin_url() ?>//Visit_Planner/fetch_customer/',
    //        type: "POST",
    //        data: {
    //            clientid:clientid
    //        },
    //        dataType: "json",
    //        success: function (res) {
    //            console.log("response",res);
    //            $('#visit_planner_to').val(res.contact.firstname+' '+res.contact.lastname);
    //            $('#visit_planner_gstno').val(res.customer.gstno);
    //            $('#visit_planner_address').val(res.customer.address);
    //            $('#visit_planner_company_name').val(res.customer.company);
    //            $('#visit_planner_city').val(res.customer.city);
    //            $('#visit_planner_state').val(res.customer.state);
    //            $('#visit_planner_zip').val(res.customer.zip);
    //            $('#visit_planner_email').val(res.contact.email);
    //            $('#visit_planner_phone').val(res.contact.phonenumber);
    //            $('#visit_planner_country').val(res.customer.country).trigger('change');
    //        }
    //    });
    //});
$("#clientid").change(function(){

        var clientid = $(this).val();
        //console.log("client_id",clientid);
        if(clientid != ""){
            $.ajax({
                url: '<?php echo admin_url() ?>Visit_Planner/fetch_customer/',
                type: "POST",
                data: {
                    clientid:clientid
                },
                dataType: "json",
                success: function (res) {
                    //console.log("response",res);
                    $('#visit_planner_to').val(res.contact.firstname+' '+res.contact.lastname);
                    $('#visit_planner_gstno').val(res.customer.gstno);
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
        }

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
    var client = "";
    $('#clientid').change(function() {
        var client = $(this).val();
        if (client != "") {
            $.ajax({
                url: '<?php echo base_url() . "admin/Visit_Planner/getContactByClient"; ?>',
                type: 'post',
                data: {
                    client: client
                },
                dataType: 'json',
                success: function(response) {
                   /* var len = response.length;
                    $("#drp_contacts").empty();
                    var options = [];
                    $.each(response, function(index, item){
                        options.push({
                            id: item[index].id,
                            text: item[index].firstname
                        });
                    });*/
                    var bs_data = [];
                    var len = response.length;
                    for(var i = 0; i < len; i++){
                        var tmp_data =  {
                            'value': response[i].id,
                            'text': response[i].firstname,
                        };
                        if(response[i].subtext){
                            tmp_data.data = {subtext:response[i].subtext}
                        }
                        bs_data.push(tmp_data);
                    }
                    return bs_data;
                    /*$("#drp_contacts").append("<option value=''>Select Contacts</option>");
                    for (var i = 0; i < len; i++) {
                        var id = response[i]['id'];
                        var firstname = response[i]['firstname'];
                        var lastname = response[i]['lastname'];
                        var phonenumber = response[i]['phonenumber'];
                        $("#drp_contacts").append("<option value='" + id + "'>" + firstname + ' ' + lastname + ' (' + phonenumber + ')' +"</option>");
                    }*/
                }
            });
            return false;
        } else {
            $("#drp_contacts").html("<option value=''>Select Contacts</option>");
        }
    });
    //$('#clientid').trigger('change');
</script>

</html>