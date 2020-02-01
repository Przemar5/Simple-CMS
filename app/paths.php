<?php

define('BASE_URL', 'http://localhost/files/Projects/SimpleCMS');
define('APP_ROOT', __DIR__);

define('MODELS_ROOT', APP_ROOT . '/app/models');
define('VIEW_ROOT', APP_ROOT . '/app/views');
define('CONTROLLERS_ROOT', APP_ROOT . '/app/controllers');
define('ROUTES', APP_ROOT . '/app/routes.php');

define('CONNECTION', APP_ROOT . '/app/connect/Connection.php');
define('AUTH', APP_ROOT . '/app/controllers/AuthController.php');
define('ERRORS', APP_ROOT . '/app/includes/errors.php');

define('ADD_PAGE', BASE_URL . '/admin/add');