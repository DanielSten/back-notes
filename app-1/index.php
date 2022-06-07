<?php

use Slim\Factory\AppFactory;

require_once './bootstrap.php';

$app = AppFactory::create();

require_once './models/Note.php';
require_once './controllers/index.php';

$app->run();