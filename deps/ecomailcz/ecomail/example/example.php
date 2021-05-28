<?php

namespace EcomailDeps;

require_once __DIR__ . '/../src/Ecomail.php';
$ecomail = new \EcomailDeps\Ecomail('API_KEY');
echo '<pre>';
\print_r($ecomail->getListsCollection());
