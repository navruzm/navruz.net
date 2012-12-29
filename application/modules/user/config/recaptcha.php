<?php

/*
  The reCaptcha server keys and API locations

  Obtain your own keys from:
  http://www.recaptcha.net
 */
$config['recaptcha'] = array(
    'public' => '6Lf_lcQSAAAAAGCB_S2AzBeACFaeOeqn_46hpkWg',
    'private' => '6Lf_lcQSAAAAANPVgWO9zxR6wVG1lDcq1Q7CLcpp',
    'RECAPTCHA_API_SERVER' => 'http://www.google.com/recaptcha/api',
    'RECAPTCHA_API_SECURE_SERVER' => 'https://www.google.com/recaptcha/api',
    'RECAPTCHA_VERIFY_SERVER' => 'www.google.com',
    'RECAPTCHA_SIGNUP_URL' => 'https://www.google.com/recaptcha/admin/create',
    'theme' => 'clean',
);
