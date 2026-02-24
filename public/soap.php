<?php
// public/soap.php

require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../app/core/Model.php';
require_once __DIR__ . '/../app/services/SoapService.php';

$options = ['uri' => 'http://localhost/'];
$server = new SoapServer(null, $options);
$server->setClass('SoapService');
$server->handle();
