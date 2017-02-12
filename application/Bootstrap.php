<?php

/**
 * Bootstrap
 *
 * @author Dean Blackborough <dean@g3d-development.com>
 * @copyright G3D Development Limited
 */
class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
    /**
     * Set PHP defaults, in this case the timesize, not safe to rely on the
     * server setting
     *
     * @return void
     */
    public function _initPhpDefaults()
    {
        // Not safe to relay on servers timezone
        if (function_exists('date_default_timezone_set')) {
            date_default_timezone_set('Europe/London');
        }
    }

    /**
     * Set doc type, set to HTML5, ensures that any code generated by the Zend
     * view helper is correct
     */
    public function _initDocType()
    {
        $this->bootstrap('layout');
        $this->getResource('layout')->getView()->doctype('HTML5');
    }

    /**
     * Connect to the default database and set the default adapter
     *
     * @return void
     */
    public function _initDatabaseConnection()
    {
        $config_options = $this->getApplication()->getOptions();

        $pdo_params = array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES UTF8;');

        $db_config_array =
            array(
                'host' => $config_options['database']['default']['host'],
                'username' => $config_options['database']['default']['user'],
                'password' => $config_options['database']['default']['password'],
                'dbname' => $config_options['database']['default']['name'],
                'driver_options' => $pdo_params,
            );

        if (strlen($config_options['database']['default']['socket']) > 0) {
            $db_config_array['unix_socket'] = $config_options['database']['default']['socket'];
        }

        $dbconn = Zend_Db::factory('Pdo_Mysql', $db_config_array);

        try {
            $dbconn->getConnection();
        } catch (Zend_Db_Adapter_Exception $e) {
            $e->getMessage();
            $e->getTraceAsString();
        } catch (Zend_Exception $e) {
            $e->getMessage();
            $e->getTraceAsString();
        }

        // Assign default adapter
        $dbconn->setFetchMode(PDO::FETCH_ASSOC);
        Zend_Db_Table_Abstract::setDefaultAdapter($dbconn);
    }
}