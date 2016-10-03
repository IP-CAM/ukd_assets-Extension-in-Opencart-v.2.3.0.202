<!--
********************************************************************************
   UKD - UKITA DEVELOPMENT
--------------------------------------------------------------------------------
   Extension : Ukd Pagseguro
   Ext. Code : ukd_pagseguro_209004f2 
   Filename  : ukd_pagseguro\upload\system\library\ukd_pagseguro_app\config.php
   Author    : Fred Ukita
   Date      : Tuesday 13th of September 2016 07:52:06 PM 
********************************************************************************
--><?php

class PagSeguroConfigWrapper
{

    /**
     * production or sandbox
     */
    const PAGSEGURO_ENV = '$enviroment';
    /**
     *
     */
    const PAGSEGURO_EMAIL = '$pagseguro_email';
    /**
     *
     */
    const PAGSEGURO_TOKEN_PRODUCTION = '$production_token';
    /**
     *
     */
    const PAGSEGURO_TOKEN_SANDBOX = '$sandbox_token';
    /**
     *
     */
    const PAGSEGURO_APP_ID_PRODUCTION = '$production_application_id';
    /**
     *
     */
    const PAGSEGURO_APP_ID_SANDBOX = '$sandbox_application_id';
    /**
     *
     */
    const PAGSEGURO_APP_KEY_PRODUCTION = '$production_application_key';
    /**
     *
     */
    const PAGSEGURO_APP_KEY_SANDBOX = '$sandbox_application_key';
    /**
     * UTF-8, ISO-8859-1
     */
    const PAGSEGURO_CHARSET = 'UTF-8';
    /**
     *
     */
    const PAGSEGURO_LOG_ACTIVE = '$log_active';
    /**
     * Informe o path completo (relativo ao path da lib) para o arquivo, ex.: ../PagSeguroLibrary/logs.txt
     */
    const PAGSEGURO_LOG_LOCATION = './pagseguro.txt';

    /**
     * @return array
     */
    public static function getConfig()
    {
        $PagSeguroConfig = array();

        $PagSeguroConfig = array_merge_recursive(
            self::getEnvironment(),
            self::getCredentials(),
            self::getApplicationEncoding(),
            self::getLogConfig()
        );

        return $PagSeguroConfig;
    }

    /**
     * @return mixed
     */
    private static function getEnvironment()
    {
        $PagSeguroConfig['environment'] = getenv('PAGSEGURO_ENV') ?: self::PAGSEGURO_ENV;

        return $PagSeguroConfig;
    }

    /**
     * @return mixed
     */
    private static function getCredentials()
    {
        $PagSeguroConfig['credentials'] = array();
        $PagSeguroConfig['credentials']['email'] = getenv('PAGSEGURO_EMAIL')
            ?: self::PAGSEGURO_EMAIL;
        $PagSeguroConfig['credentials']['token']['production'] = getenv('PAGSEGURO_TOKEN_PRODUCTION')
            ?: self::PAGSEGURO_TOKEN_PRODUCTION;
        $PagSeguroConfig['credentials']['token']['sandbox'] = getenv('PAGSEGURO_TOKEN_SANDBOX')
            ?: self::PAGSEGURO_TOKEN_SANDBOX;
        $PagSeguroConfig['credentials']['appId']['production'] = getenv('PAGSEGURO_APP_ID_PRODUCTION')
            ?: self::PAGSEGURO_APP_ID_PRODUCTION;
        $PagSeguroConfig['credentials']['appId']['sandbox'] = getenv('PAGSEGURO_APP_ID_SANDBOX')
            ?: self::PAGSEGURO_APP_ID_SANDBOX;
        $PagSeguroConfig['credentials']['appKey']['production'] = getenv('PAGSEGURO_APP_KEY_PRODUCTION')
            ?: self::PAGSEGURO_APP_KEY_PRODUCTION;
        $PagSeguroConfig['credentials']['appKey']['sandbox'] = getenv('PAGSEGURO_APP_KEY_SANDBOX')
            ?: self::PAGSEGURO_APP_KEY_SANDBOX;

        return $PagSeguroConfig;
    }

    /**
     * @return mixed
     */
    private static function getApplicationEncoding()
    {
        $PagSeguroConfig['application'] = array();
        $PagSeguroConfig['application']['charset'] = ( getenv('PAGSEGURO_CHARSET')
            && ( getenv('PAGSEGURO_CHARSET') == 'UTF-8' || getenv('PAGSEGURO_CHARSET') == 'ISO-8859-1') )
            ?: self::PAGSEGURO_CHARSET;

        return $PagSeguroConfig;
    }

    /**
     * @return mixed
     */
    private static function getLogConfig()
    {
        $PagSeguroConfig['log'] = array();
        $PagSeguroConfig['log']['active'] = ( getenv('PAGSEGURO_LOG_ACTIVE')
            && getenv('PAGSEGURO_LOG_ACTIVE') == 'true' ) ?: self::PAGSEGURO_LOG_ACTIVE;
        $PagSeguroConfig['log']['fileLocation'] = getenv('PAGSEGURO_LOG_LOCATION')
            ?: self::PAGSEGURO_LOG_LOCATION;

        return $PagSeguroConfig;
    }
}
