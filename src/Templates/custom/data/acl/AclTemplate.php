<?php

//
// Enrico Simonetti
// enricosimonetti.com
//
// 2015-09-10 on Sugar 7.6.0.0
// filename: custom/data/acl/SugarACLReadOnly.php
//
// Read only ACL except for Admin users and specific user ids
//
// Changes to this class might require the user browser's storage and cache to be cleared, to work correctly.
//

class AclTemplateName extends SugarACLStrategy
{
    // allowed user ids
    protected $user_ids_to_allow = [];

    // denied actions
    protected $denied_actions = [
        'edit',
        'delete',
        'massupdate',
        'import',
    ];

    // our custom method to check permissions
    protected function _canUserWrite($context)
    {
        // retrieve user from context
        $user = $this->getCurrentUser($context);

        if (isset($context['bean'])) {
            $bean = $context['bean'];
        } else {
            return true;
        }

        $this->user_ids_to_allow[] = $bean->created_by;

        $user->load_relationship('aclroles');
        $roles = ACLRole::getUserRoles($user->id, true);

        $userAllowedRoles = array_intersect(['C-Level', 'Head of Department'], $roles);

        if ('Closed Won' == $bean->sales_stage) {
            // allow only admin users or special users to write
            // if($user->isAdmin() || in_array($user->id, $this->user_ids_to_allow) || count($userAllowedRoles)) {
            if ($user->isAdmin() || count($userAllowedRoles)) {
                return true;
            } else {
                return false;
            }
        }

        return true;
    }

    // runtime access check
    public function checkAccess($module, $view, $context)
    {
        $view = SugarACLStrategy::fixUpActionName($view);
        // if it is not a blocked action, or there is no bean, allow it
        if (!in_array($view, $this->denied_actions) || !isset($context['bean'])) {
            return true;
        }

        // can user write?
        if ($this->_canUserWrite($context)) {
            return true;
        }

        // everyone else for everything else is denied
        return false;
    }

    // mostly for front-end access checks (cached on the application, per user)
    public function getUserAccess($module, $access_list = [], $context = [])
    {
        // retrieve original ACL
        $acl = parent::getUserAccess($module, $access_list, $context);

        // if user can't write
        if (!$this->_canUserWrite($context)) {
            // override access, disable access where required if not admin and not special user
            foreach ($acl as $access => $value) {
                if (in_array($access, $this->denied_actions)) {
                    $acl[$access] = 0;
                }
            }
        }

        // return modified acl
        return $acl;
    }
}
