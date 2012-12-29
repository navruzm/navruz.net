<?php if ($this->auth->is_user()): ?>
    <?php echo anchor('user/logout', 'Logout'); ?>
<?php else: ?>
    <?php echo anchor('user/register', 'Register'); ?>
    <?php echo anchor('user/login', 'Login'); ?>
<?php endif; ?>