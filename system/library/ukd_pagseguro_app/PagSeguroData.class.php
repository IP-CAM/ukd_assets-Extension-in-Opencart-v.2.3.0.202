<!--
********************************************************************************
   UKD - UKITA DEVELOPMENT
--------------------------------------------------------------------------------
   Extension : Ukd Pagseguro
   Ext. Code : ukd_pagseguro_209004f2 
   Filename  : ukd_pagseguro\upload\system\library\ukd_pagseguro_app\PagSeguroData.class.php
   Author    : Fred Ukita
   Date      : Tuesday 13th of September 2016 07:52:06 PM 
********************************************************************************
--><?php

class PagSeguroData
{
    private $sandbox;

    private $sandboxData = array(

		'credentials' => array(
			'email' => 'fredukita@gmail.com',
			'token' => '9E7583A0AB8F44EC9E6D72FB81C4693F',
		),

		'sessionURL' => 'https://ws.sandbox.pagseguro.uol.com.br/v2/sessions',
		'transactionsURL' => 'https://ws.sandbox.pagseguro.uol.com.br/v2/transactions',
		'javascriptURL' => 'https://stc.sandbox.pagseguro.uol.com.br/pagseguro/api/v2/checkout/pagseguro.directpayment.js',
	);

    private $productionData = array(

		'credentials' => array(
			'email' => 'Your e-mail',
			'token' => 'Your token',
		),

		'sessionURL' => 'https://ws.pagseguro.uol.com.br/v2/sessions',
		'transactionsURL' => 'https://ws.pagseguro.uol.com.br/v2/transactions',
		'javascriptURL' => 'https://stc.pagseguro.uol.com.br/pagseguro/api/v2/checkout/pagseguro.directpayment.js',

	);

    public function __construct($sandbox = false)
    {
        $this->sandbox = (bool) $sandbox;
    }

    private function getEnviromentData($key)
    {
        if ($this->sandbox) {
            return $this->sandboxData[$key];
        } else {
            return $this->productionData[$key];
        }
    }

    public function getSessionURL()
    {
        return $this->getEnviromentData('sessionURL');
    }

    public function getTransactionsURL()
    {
        return $this->getEnviromentData('transactionsURL');
    }

    public function getJavascriptURL()
    {
        return $this->getEnviromentData('javascriptURL');
    }

    public function getCredentials()
    {
        return $this->getEnviromentData('credentials');
    }

    public function isSandbox()
    {
        return (bool) $this->sandbox;
    }
}
