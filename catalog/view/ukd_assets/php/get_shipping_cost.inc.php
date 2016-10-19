<?php


if((int)$weight > 30){
  $frete['error'] = 'Limite de peso exede o máximo permitido de 30Kg . O peso atual do pedido é de '.(string)$weight.'Kg.';
  $frete['pac'] = 'não disponível';
  $frete['sedex'] = 'não disponível';
}
else{

$params = [];

// Código e senha da empresa, se você tiver contrato com os correios, se não tiver deixe vazio.
$params['nCdEmpresa'] = $login;
$params['sDsSenha'] = $senha;

// CEP de origem e destino. Esse parametro precisa ser numérico, sem "-" (hífen) espaços ou algo diferente de um número.
$params['sCepOrigem'] = $cep;
$params['sCepDestino'] = $cep_dest;

// O peso do produto deverá ser enviado em quilogramas(Kg), leve em consideração que isso deverá incluir o peso da embalagem.
$params['nVlPeso'] = $weight;

// O formato tem apenas duas opções: 1 para caixa / pacote e 2 para rolo/prisma.
$params['nCdFormato'] = '1';

// O comprimento, altura, largura e diametro deverá ser informado em centímetros e somente números
$params['nVlComprimento'] = '16';
$params['nVlAltura'] = '5';
$params['nVlLargura'] = '15';
$params['nVlDiametro'] = '0';

// Aqui você informa se quer que a encomenda deva ser entregue somente para uma determinada pessoa após confirmação por RG. Use "s" e "n".
$params['sCdMaoPropria'] = 's';

// O valor declarado serve para o caso de sua encomenda extraviar, então você poderá recuperar o valor dela. Vale lembrar que o valor da encomenda interfere no valor do frete. Se não quiser declarar pode passar 0 (zero).
$params['nVlValorDeclarado'] = '0';

// Se você quer ser avisado sobre a entrega da encomenda. Para não avisar use "n", para avisar use "s".
$params['sCdAvisoRecebimento'] = 'n';

// Formato no qual a consulta será retornada, podendo ser: Popup é mostra uma janela pop-up - URL é envia os dados via post para a URL informada - XML é Retorna a resposta em XML
$params['StrRetorno'] = 'xml';

// Código do Serviço, pode ser apenas um ou mais. Para mais de um apenas separe por virgula.
// 40010 SEDEX Varejo
// 40045 SEDEX a Cobrar Varejo
// 40215 SEDEX 10 Varejo
// 40290 SEDEX Hoje Varejo
// 41106 PAC Varejo
$params['nCdServico'] = '40010,41106';

$params = http_build_query($params);
$url = 'http://ws.correios.com.br/calculador/CalcPrecoPrazo.aspx';
$curl = curl_init($url.'?'.$params);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_FAILONERROR, true);
$resp = curl_exec($curl);

$frete = [];
//$frete['error'] = [];

$service_type = [
  '40010' => 'sedex',
  '40045' => 'sedex a cobrar',
  '40215' => 'sedex 10',
  '40290' => 'sedex hoje',
  '41106' => 'pac'];

if (curl_error($curl) || $resp === false) {
    $frete['error'] = curl_error($curl) ;
} else {
    libxml_use_internal_errors(true);
    $resp = simplexml_load_string($resp);
    if ($resp === false) {
        foreach (libxml_get_errors() as $error) {
            $frete['error'] = $error->message;
        }
    } else {
        foreach ($resp->cServico as $row) {
            if ($row->Erro == 0) {
                $frete[$service_type["$row->Codigo"]] = ['valor' => "$row->Valor", 'prazo' => "$row->PrazoEntrega"];
            } else {
                $frete['error'] = $row->MsgErro;
            }
        }
    }
}

curl_close($curl);

}
