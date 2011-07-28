<?php
$this->load->helper('permission');
$modules = get_module_has_admin_menu();
$permissions = $this->permissions_model->get($this->uri->segment(4));
?>
<?php echo form_open($this->uri->uri_string(), array('class' => 'yform columnar')); ?>
<?php echo form_fieldset('Üye İzinlerini Değiştirin'); ?>
<?php foreach ($modules as $module): ?>
    <div class="type-select">
    <?php echo form_label($module['module_name'], $module['module'] . '_module' . $module['admin_controller_name']); ?>
    <?php
    echo form_dropdown(
            'module[' . $module['module'] . '/' . $module['admin_controller_name'] . ']',
            array('0' => 'Hayır', '1' => 'Evet'),
            (@$permissions[$module['module'] . '/' . $module['admin_controller_name']] == 1) ? 1 : 0,
            'id="' . $module['module'] . '_module' . $module['admin_controller_name'] . '"');
    ?>
</div>
<?php endforeach; ?>
    <div class="buttonbox">
    <?php echo form_submit('change', 'Değiştir', 'class="awesome"'); ?>
</div>
<?php echo form_fieldset_close(); ?>
<?php echo form_close(); ?>