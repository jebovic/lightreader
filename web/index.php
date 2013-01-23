<?php
// web/index.php

$app = require __DIR__.'/../src/app.php';

$app['debug'] ? $app->run() : $app['http_cache']->run();
exit;