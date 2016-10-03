<?php

if(isset($_POST) && !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
  /* special ajax here */
  require_once '../../../../../system/library/PagSeguroLibrary/PagSeguroLibrary.php';
  ob_clean();
  $data = [];
  try {
      $credentials = new PagSeguroAccountCredentials($_POST['email'] , $_POST['token']);
      $data['sessionId'] = PagSeguroSessionService::getSession($credentials);
  } catch (PagSeguroServiceException $e) {
      //die($e->getMessage());
      $data['error'] = $e->getMessage();
  }
  header('Content-Type: application/json');
  exit(json_encode($data));

}
ob_clean();
header("HTTP/1.1 301 Moved Permanently");
header("Location: /");
exit;