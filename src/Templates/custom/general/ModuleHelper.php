<?php

namespace Sugarcrm\Sugarcrm\custom\general;

/**
 * Class OpportunityDatesModuleHelper
 * Contains methods that should be shared among classes
 */
class ModuleHelper
{
    /**
     * @param $bean
     * @param array $attributes    list of attributes that need to be checked
     * @param array $oldAttributed values of old attributes of the bean
     *
     * @return bool
     */
    public static function isBeanChanged($bean, array $attributes, array $oldAttributed)
    {
        $isChanged = false;

        foreach ($attributes as $attribute) {
            if (isset($bean->$attribute) && array_key_exists($attribute, $oldAttributed)) {
                $isChanged = $isChanged || $bean->$attribute != $oldAttributed[$attribute] ? true : false;
            }
        }

        return $isChanged;
    }

    /**
     * Checks if bean is new
     *
     * @param $bean
     *
     * @return bool
     */
    public static function isNew($bean)
    {
        if ($bean->fetched_row) {
            return false;
        }

        return true;
    }

    /**
     * Checks if module related by it's argument to another
     *
     * @param $arguments
     * @param $modulesList
     *
     * @return bool
     */
    public static function isArgumentsRelatedTo($arguments, $modulesList)
    {
        if (isset($arguments['related_module']) && in_array($arguments['related_module'], $modulesList)) {
            return true;
        }

        return false;
    }

    /**
     * Get parent relationship
     *
     * @param $bean
     * @param $parentRelationName
     *
     * @return mixed|null
     */
    public static function getParent($bean, $parentRelationName)
    {
        $parentBean = null;

        if ($bean->load_relationship($parentRelationName)) {
            //Fetch related beans
            $relatedBeans = $bean->$parentRelationName->getBeans();

            if (!empty($relatedBeans)) {
                //order the results
                reset($relatedBeans);

                //first record in the list is the parent
                $parentBean = current($relatedBeans);
            }
        }

        return $parentBean;
    }

    /**
     * Get human readable interval string
     *
     * @param DateInterval $interval
     *
     * @return string
     */
    public static function getHumanInterval(DateInterval $interval)
    {
        if ($interval->y) {
            $reaction_time_human_c[] = $interval->y . ' Years';
        }

        if ($interval->m) {
            $reaction_time_human_c[] = $interval->m . ' Month';
        }

        $reaction_time_human_c[] = $interval->d . ' Days';
        $reaction_time_human_c[] = $interval->h . ' Hours';
        $reaction_time_human_c[] = $interval->i . ' Minutes';

        return implode(' ', $reaction_time_human_c);
    }
}
