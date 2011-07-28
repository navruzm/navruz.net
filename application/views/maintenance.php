<?php
add_css('style');
add_js('js');
add_js('countdown');
add_jquery('$("#countdown").countdown({
                    until: new Date('.date('Y',  get_option('maintenance-end')).', '.date('m',  get_option('maintenance-end')).' - 1, '.date('j',  get_option('maintenance-end')).', '.date('H',  get_option('maintenance-end')).', '.date('i',  get_option('maintenance-end')).'),
                    layout: "{dn} {dl}, {hn} {hl}, {mn} {ml}, {sn} {sl}"
                });');
add_css('', 'embed');
?>
<div class="page_margins" style="width:500px;padding: 10px;font-size: 1.4em">
    <div class="page" style="background:#fff url(assets/img/maintenance.png) no-repeat center right;padding: 10px 20px;">
        <?php echo heading(get_option('site_name'),2); ?><br/>
        <?php echo get_option('maintenance-message'); ?>
        <hr/>
        <div style="text-align: center">Ortalama bitiş süresi:</div>
        <div id="countdown" style="text-align: center;font-size: 1.2em"></div>
        
    </div>
</div>
<?php echo analytics_code(); ?>