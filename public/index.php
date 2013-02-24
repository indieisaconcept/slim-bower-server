<?php

require_once '../app/vendor/slim/slim/Slim/slim.php';
require_once '../app/vendor/j4mie/idiorm/idiorm.php';
require_once '../app/vendor/j4mie/paris/paris.php';

$app = new Slim();

require_once '../app/config.php';
require_once '../app/data/config.php';
require_once '../app/routes/default.php';
require_once '../app/routes/packages.php';

$app->run();

?>