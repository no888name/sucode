<?php

if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}
require_once('clients/base/api/ModuleApi.php');

class SampleApi extends ModuleApi
{
    public function registerApiRest()
    {
        return [
            'postsettings' => [
                'reqType' => ['POST'],
                //set authentication
                'noLoginRequired' => false,
                //endpoint path
                'path' => [':class_name', 'settings'],
                //endpoint variables
                'pathVars' => ['', ''],

                //method to call
                'method' => 'postSettings',

                //short help string to be displayed in the help documentation
                'shortHelp' => 'Get settings',
            ],
            'getsettings' => [
                'reqType' => ['GET'],
                //set authentication
                'noLoginRequired' => false,
                //endpoint path
                'path' => [':class_name', 'settings'],
                //endpoint variables
                'pathVars' => ['', ''],

                //method to call
                'method' => 'getSettings',

                //short help string to be displayed in the help documentation
                'shortHelp' => 'Set settings',
            ],
            'scheduleSomething' => [
                //request type
                'reqType' => 'POST',

                //endpoint path
                'path' => [':class_name', 'schedule'],

                //endpoint variables
                'pathVars' => ['', ''],

                //method to call
                'method' => 'scheduleCsat',

                //short help string to be displayed in the help documentation
                'shortHelp' => 'send survey mail',
            ],
        ];
    }

    /**
     * @param $api
     * @param $args
     *
     * @return array
     */
    public function getSettings($api, $args)
    {
        $config = [
            'enables' => [
                [
                    'name' => 'Enable CSAT Survey',
                    'key' => 'csat',
                    'value' => true,
                ],
                [
                    'name' => 'Enable NPS Survey',
                    'key' => 'nps',
                    'value' => true,
                ],
            ],

            'automatically' => [
                [
                    'name' => 'Send CSAT Survey automatically',
                    'key' => 'csat_auto',
                    'value' => true,
                ],

                [
                    'name' => 'Send NPS Survey automatically',
                    'key' => 'nps_auto',
                    'value' => true,
                ],
            ],
            'numbers' => [
                ['name' => 'Max Surveys In A Day for Customer', 'key' => 'max_in_a_day', 'value' => '2'],
                ['name' => 'Automatically Send After Days', 'key' => 'autosend_after_days', 'value' => '1'],
            ],
        ];
//        $administrationObj = new \Administration();
//        $administrationObj->saveSetting('npsSendItems', 'npsSendItems', json_encode($config));

        $administrationObj = new Administration();
        $configArray = $administrationObj->retrieveSettings('npsSendItems')->settings['npsSendItems_npsSendItems'];

        return $configArray;
    }

    /**
     * @param $api
     * @param $args
     *
     * @return array
     *
     * @throws \Doctrine\DBAL\DBALException
     */
    public function postSettings($api, $args)
    {
        $administrationObj = new Administration();

        $administrationObj->saveSetting('npsSendItems', 'npsSendItems', json_encode([
            'enables' => $args['enables'],
            'automatically' => $args['automatically'],
            'numbers' => $args['numbers'],
        ]));

        //do cache clear in order changes take effect
//        $configuratorObj = new Configurator();
//        $configuratorObj->clearCache();

        return ['success' => true];
    }

    /**
     * @param $api
     * @param $args
     *
     * @return array
     *
     * @throws \Doctrine\DBAL\DBALException
     */
    public function schedule($api, $args)
    {
        global $current_user;

        global $db;



        $job = BeanFactory::getBean('SchedulersJobs', null);
        $job->name = 'Some Reminder';
        $job->target = 'class::' . \Sugarcrm\Sugarcrm\custom\jobs\CustomJobScheduler::class;
        $job->retry_count = 0;
        $job->assigned_user_id = $current_user->id;
        $job->job_delay = 24 * 60 * 60;
        $job->module = $args['id'];

        $job->data = json_encode([
            'parent_id' => $args['id'],
            'contactId' => $args['contactId'],
        ]);


        $jq = new SugarJobQueue();
        $jq->submitJob($job);

        return [
            'success' => true,
        ];
    }
}
