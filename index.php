<?php

require_once "vendor/autoload.php";

use App\App;
use App\Http\Request;

//===========================================================
$req = Request::createFromGlobals();
$app = new App($req);

if ($req->isMethodPost()) {
    $app->processPost();
    exit();
}

if ($req->isMethodGet()) {
    $app->processGet();
    exit();
}
