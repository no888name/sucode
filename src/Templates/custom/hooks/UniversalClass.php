<?php

namespace Sugarcrm\Sugarcrm\custom\hooks;

use BeanFactory;
use Sugarcrm\Sugarcrm\custom\general\ModuleHelper;

/**
 * Class :className
 *
 * @package Sugarcrm\Sugarcrm\custom\hooks
 */
class :className
{
    public function beforeSave($bean, $event, $arguments)
    {
        $bean->oldAttributes = $bean->fetched_row ? $bean->fetched_row : [];

        if (ModuleHelper::isBeanChanged($bean, ['status'], $bean->oldAttributes)) {
            $bean->needToUpdateWarning = true;
        }

        if (ModuleHelper::isNew($bean)) {
            $bean->needToUpdateWarning = true;
        }
    }

    public function afterSave($bean, $event, $arguments)
    {
        global $current_user, $sugar_config;

        if ($bean->ignore_this_in_hooks) {
            return;
        }

    }

    public function afterRel($bean, $event, $arguments)
    {
        if ($arguments['related_module'] === 'Accounts') {
            //do staff
        }


    }

}
