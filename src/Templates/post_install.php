<?php

if (!defined('sugarEntry')) {
    define('sugarEntry', true);
}

function post_install()
{
    //do repair
    $repair = new RepairAndClear();

    $repair->repairAndClearAll(
        ['clearAll'],
        [],
        true,//autoexecute
        true//show output
    );
}
