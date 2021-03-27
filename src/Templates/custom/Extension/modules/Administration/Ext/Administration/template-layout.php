<?php

$admin_option_defs = [];

$admin_option_defs['Administration'][':template-layout-name'] = [
    'fa-clock', //icon mame
    ':LBL_LAYOUT_NAME', //link name lable
    ':LBL_LAYOUT_DESC', //link description label
    'javascript:parent.SUGAR.App.router.navigate("Home/layout/:template-layout-name", {trigger: true});', ////Link URL - For Sidecar modules
    //'./index.php?module=Administration&action=reparesalutation', //Alternatively, if you are linking to BWC modules
];

$admin_group_header[] = [':LBL_LAYOUT_NAME', '', false, $admin_option_defs, ''];
