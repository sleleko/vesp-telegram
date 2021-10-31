<?php

use Vesp\Helpers\Env;
use Vesp\Services\Eloquent as LocalEloquent;

define('BASE_DIR', dirname(__FILE__, 3) . '/');
require BASE_DIR . 'core/vendor/autoload.php';

Env::loadFile(BASE_DIR . 'core/.env');
new LocalEloquent();