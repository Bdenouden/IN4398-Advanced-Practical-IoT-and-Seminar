<?php

header('X-Frame-Options: SAMEORIGIN'); // clickjacking
header('Strict-Transport-Security: max-age=31536000; includeSubDomains; preload'); // HSTS.
header('X-Content-Type-Options: nosniff'); // NoSniff.
header('X-XSS-Protection: 1; mode=block'); // XSS.
header('Expires: 0'); // Proxies.

session_start(); 

require_once('core/config.php');
require_once('core/exceptions.php');

require_once('core/database.php');

require_once('core/model.php');
require_once('core/controller.php');

require_once('core/auth.php');
require_once('core/form.php');
require_once('core/user.php');
require_once('core/page.php');

require_once('core/helper_functions.php');

$page = new Page();
