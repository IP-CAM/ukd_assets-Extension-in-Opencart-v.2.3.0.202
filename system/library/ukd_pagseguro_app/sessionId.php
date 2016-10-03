<!--
********************************************************************************
   UKD - UKITA DEVELOPMENT
--------------------------------------------------------------------------------
   Extension : Ukd Pagseguro
   Ext. Code : ukd_pagseguro_209004f2 
   Filename  : ukd_pagseguro\upload\system\library\ukd_pagseguro_app\sessionId.php
   Author    : Fred Ukita
   Date      : Tuesday 13th of September 2016 07:52:06 PM 
********************************************************************************
--><?php

require_once '../PagSeguroLibrary/PagSeguroLibrary.php';
require_once 'PagSeguroData.class.php';

$pagSeguroData = new PagSeguroData(true);

$credentials = $pagSeguroData->getCredentials();

try {
    $credentials = new PagSeguroAccountCredentials($credentials['email'], $credentials['token']);
    echo PagSeguroSessionService::getSession($credentials);
} catch (PagSeguroServiceException $e) {
    die($e->getMessage());
}
