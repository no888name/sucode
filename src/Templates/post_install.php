<?php

if (!defined('sugarEntry')) {
    define('sugarEntry', true);
}

function post_install()
{
    $config = [
        'url' => 'someurl',
        'secret' => 'somesecret',
    ];
    $administrationObj = new \Administration();
    $administrationObj->saveSetting('packageSettings', 'packageSettings', json_encode($config));

    //do repair
    $repair = new RepairAndClear();

    $repair->repairAndClearAll(
        ['clearAll'],
        [],
        true,//autoexecute
        true//show output
    );
}
