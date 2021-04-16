<?php

//http://{sugar url}/index.php?entryPoint=customEntryPoint
$entry_point_registry[':customEntryPoint'] = array(
    'file' => 'custom/:customEntryPoint.php',
    'auth' => true
);