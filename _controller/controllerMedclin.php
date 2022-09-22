<?php
/**
 * Description of controller Medclin
 *
 * @author welingtonmarquezini
 */
    ob_start();//inicio da sessao 
    session_start();//inicio da sessao 
    require_once '../_model/medclin.php';
    //conexao
    require_once '../_model/urlDb.php';
    $url = new UrlBD();
    $url->inicia();
    $dsn = $url->getDsn();
    $username = $url->getUsername();
    $passwd = $url->getPasswd();
    try {
        $conexao = new \PDO($dsn, $username, $passwd);//cria conexão com banco de dadosX 
    } catch (\PDOException $ex) {
        die('Não foi possível estabelecer '
        . 'conexão com Banco de dados<br/>Erro Nº=> ' . $ex->getCode());
    }
    $entrevista = new medclin($conexao);
    /* Esta é a maneira correta de se declarar uma superglobal */
    $post = filter_input_array(INPUT_POST, FILTER_DEFAULT); 
    $get = filter_input_array(INPUT_GET, FILTER_DEFAULT);
    //verificar a rota post ou get
    $REQUEST = (isset($post['Funcao'])) ? filter_input_array(INPUT_POST, FILTER_DEFAULT) : filter_input_array(INPUT_GET, FILTER_DEFAULT);
    
    $matricula = trim(strip_tags($REQUEST['Mat']));
    $tipo = strtolower(trim(strip_tags($REQUEST['Tipo'])));
    $datatxt = trim(strip_tags($REQUEST['data']));
    $data = $url->converterData($datatxt);
    $prontuario = trim(strip_tags($REQUEST['prontuario']));
    $cidtxt = trim(strip_tags($REQUEST['cid']));
    $cid = substr($cidtxt,0,3);
    $tempo = trim(strip_tags($REQUEST['tempo']));
    $unidtempo = trim(strip_tags($REQUEST['unidtempo']));
    $inicio = trim($url->converterData(strip_tags($REQUEST['Inicio'])));
    $fim = trim($url->converterData(strip_tags($REQUEST['Fim'])));
    $obs = trim(strip_tags($REQUEST['Obs']));
    $id  = $matricula . "_" . date("Y-m-d", strtotime($data));

    $funcao = trim(strip_tags($REQUEST['Funcao']));
    
    $entrevista->setMatricula($matricula);
    $entrevista->setTipo($tipo);
    $entrevista->setDataAtend($data);
    $entrevista->setProntuario($prontuario);
    $entrevista->setCid($cid);
    $entrevista->setTempo($tempo);
    $entrevista->setUnidtempo($unidtempo);
    $entrevista->setInicio($inicio);
    $entrevista->setFim($fim);
    $entrevista->setObs($obs);
    $entrevista->setId($id);

switch ($funcao) {
    case 1:
        $entrevista->inserir();
        break;
   case 2:
        $entrevista->alterar();
        break;
   case 3:
        $entrevista->deletar($id);
        break;
   default :
        break;
}
header("Location: ../medclin.php");exit;