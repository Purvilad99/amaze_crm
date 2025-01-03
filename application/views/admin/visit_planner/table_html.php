<?php defined('BASEPATH') or exit('No direct script access allowed');


defined('BASEPATH') or exit('No direct script access allowed');

$table_data = [
    _l('visit_planner_select_customer'),
  _l('visit_planner_type'),
  _l('visit_planner_currency'),
  _l('visit_planner_total_gold'),
  _l('visit_planner_date')
];
$custom_fields = get_custom_fields('visit_planner',array('show_on_table'=>1));
foreach($custom_fields as $field){
    array_push($table_data, [
        'name' => $field['name'],
        'th_attrs' => array('data-type'=>$field['type'], 'data-custom-field'=>1)
    ]);
}
$table_data = hooks()->apply_filters('visit_planner_table_columns', $table_data);

render_datatable($table_data, (isset($class) ? $class : 'visit_planner'), [], ['id'=>$table_id ?? 'visit_planner']);

?>
