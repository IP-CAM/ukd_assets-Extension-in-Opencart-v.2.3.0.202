<!--











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