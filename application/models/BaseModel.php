<?php
/*
 * @method load
 */
class BaseModel
{
    /**
     * __get magic
     *
     * Allows models to access CI's loaded classes using the same
     * syntax as controllers.
     *
     * @param	string	$key
     */
    public function __get($key)
    {
        // Debugging note:
        //	If you're here because you're getting an error message
        //	saying 'Undefined Property: system/core/Model.php', it's
        //	most likely a typo in your model code.
        return get_instance()->$key;
    }

    /**
     * Get db instance
     *
     * @return CI_DB_query_builder
     */
    protected static function db($group_name = '')
    {
        $CI =& get_instance();

        if ($group_name)
        {
            return $CI->load->database($group_name, true);
        }

        $CI->load->database();

        return $CI->db;
    }

    /**
     * Get load instance
     *
     * @return CI_Loader
     */
    protected static function load()
    {
        return get_instance()->load;
    }
}