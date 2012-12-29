<?php echo form_open_multipart($this->uri->uri_string(), array('class' => 'yform full')); ?>
<?php echo form_item('name', 'İsim', 'input', array('value' => set_value('name', isset($name) ? $name : ''))); ?>
<?php echo form_item('email', 'E-Posta', 'input', array('value' => set_value('email', isset($email) ? $email : ''))); ?>
<?php echo form_item('password', 'Yeni Şifre'); ?>
<?php echo form_item('confirm_password', 'Yeni Şifre Tekrarı'); ?>
<div class="clearfix">
    <label id="optionsCheckboxes">Yönetim izni verilen modüller</label>
    <div class="input">
        <ul class="inputs-list">
            <li>
                <label>
                    <?php $checked = isset($permissions[':all:']) ? TRUE : FALSE; ?>
                    <?php echo form_checkbox('permissions[:all:]', 1, $checked, 'id="check-all"'); ?>
                    <span>Tümüne İzin Ver</span>
                </label>
            </li>
            <?php foreach (get_module_has_admin_menu() as $module): ?>
                <?php $perm = $module['module'] . '/' . $module['admin_controller_name']; ?>
                <?php $disabled = $checked === TRUE ? ' disabled' : ''; ?>
                <li>
                    <label>      
                        <?php
                        echo form_checkbox('permissions[' . $perm . ']', 1, isset($permissions[$perm]) ? TRUE : FALSE, 'class="module-role"' . $disabled);
                        ?>
                        <span><?php echo $module['module_name']; ?></span>
                    </label>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</div>
<div class="actions">
    <button type="submit" class="btn primary">Gönder</button>
</div>
<?php echo form_close(); ?>
<?php echo add_js_jquery('$("#check-all").live("click", function () {
        var all = $(this);
        all.closest("ul").find(".module-role").each(function () {
            if (all.is(":checked")) {
                $(this).attr("disabled", true);
            } else if ( ! all.is(":checked")) {
                $(this).attr("disabled", false);
            }
            
        });
    });'); ?>
