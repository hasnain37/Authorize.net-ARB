<?php

class Activation
{

    /**
     * Short Description. (use period)
     *
     * Long Description.
     *
     * @since 1.0.0
     */
    public static function activate()
    {
        $role = get_role('administrator');
        if (!empty($role)) {
            $role->add_cap('my_plugin_manage');
        }
    }
}
