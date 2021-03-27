<?php

//Loop through the groupings to find include/javascript/sugar_grp7.min.js
foreach ($js_groupings as $key => $groupings) {
    foreach ($groupings as $file => $target) {
        if ('include/javascript/sugar_grp7.min.js' == $target) {
            //append the custom helper file
            $js_groupings[$key]['custom/include/:fileName.js'] = 'include/javascript/sugar_grp7.min.js';
        }

        break;
    }
}
