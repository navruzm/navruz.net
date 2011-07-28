<?php

$birthday = ($profile['birthday'] == 0) ? '' : @date('d-m-Y', $profile['birthday']);
$birthday2 = ($profile['birthday'] == 0) ? '' : @date('Y-m-d', $profile['birthday']);

add_js('js');
add_js('dateinput');
add_css('tools');
add_jquery('

$.tools.dateinput.localize("tr",  {
   months:        \'Ocak,Şubat,Mart,Nisan,Mayıs,Haziran,Temmuz,Ağustos,Eylül,Ekim,Kasım,Aralık\',
   shortMonths:   \'Oca,Şub,Mar,Nis,May,Haz,Tem,Ağu,Eyl,Eki,Kas,Ara\',
   days:          \'Pazartesi,Salı,Çarşamba,Perşembe,Cuma,Cumartesi,Pazar\',
   shortDays:     \'Pts,Sal,Çar,Per,Cum,Cts,Paz\',
});
$("#birthday").dateinput({
        lang: \'tr\',
	format: \'dd-mm-yyyy\',
	selectors: true,             	
	offset: [10, 200],
	speed: \'fast\',
        yearRange: [-80, 1],
	firstDay: 1,
        value: \'' . $birthday2 . '\'
});');
$this->load->view('control_panel');
?>
<?php echo form_open(config_item('auth_uri_change_profile'), array('class' => 'yform columnar')); ?>
<div class="type-text">
<?php echo form_item('first_name', 'Adınız', 'input', array('value' => $profile['first_name'])); ?>
</div>
<div class="type-text">
<?php echo form_item('last_name', 'Soyadınız', 'input', array('value' => $profile['last_name'])); ?>
</div>
<div class="type-text">
<?php echo form_item('bio', 'Hakkınızda', 'textarea', array('value' => $profile['bio'])); ?>
</div>
<div class="type-text">
<?php echo form_item('birthday', 'Doğum Tarihiniz', 'input', array('value' => $birthday)); ?>
</div>
<div class="type-text">
<?php echo form_item('job', 'İşiniz', 'input', array('value' => $profile['job'])); ?>
</div>
<div class="type-text">
<?php echo form_item('location', 'Bulunduğunuz Şehir', 'input', array('value' => $profile['location'])); ?>
</div>
<div class="subcl type-select">
<?php echo form_label('Cinsiyet', 'gender'); ?>
    <?php echo form_dropdown('gender', array('' => 'Seçiniz.', 'm' => 'Bay', 'f' => 'Bayan'), set_value('gender', $profile['gender']), 'id="gender"'); ?>
    <?php echo form_error('gender'); ?>
</div>
<div class="buttonbox">
<?php echo form_submit('change', 'Değiştir', 'class="awesome"'); ?>
</div>
<?php echo form_close(); ?>