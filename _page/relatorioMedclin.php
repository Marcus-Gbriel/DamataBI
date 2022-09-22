<?php ob_start();//inicio da sessao 
    session_start();//inicio da sessao
    require_once '../_model/session.php';
    $session = new session();
    $session->logarUser(); //verificar se esta logado
    require_once '../_model/urlDb.php';
    $url = new UrlBD();
    $url->iniciaAPI();
    $dsn = $url->getDsn();
    $username = $url->getUsername();
    $passwd = $url->getPasswd();
    $url->logarUser();
    try {
        $conexao= new \PDO($dsn, $username, $passwd);//cria conexão com banco de dados 
    } catch (\PDOException $ex) {
        die('Não foi possível estabelecer '
        . 'conexão com Banco de dados<br/>Erro Nº=> ' . $ex->getCode());
    }
    //relatório
    if (isset($_REQUEST['rel'])) {
        require_once '../_model/medclin.php';
        $entrevista = new medclin($conexao);
        $resultado = $entrevista->consult();
        // Definimos o nome do arquivo que será exportado
        $arquivo = 'Medclin.xls';
        // Criamos uma tabela HTML com o formato da planilha
        $html = '';
        $html .= '<table border="0">';
        $html .= '<tr>';
        $html .= '<td><b>Data Atend.</b></td>';
        $html .= '<td><b>Matrícula</b></td>';
        $html .= '<td><b>Colaborador</b></td>';
        $html .= '<td><b>Cargo</b></td>';
        $html .= '<td><b>Prontuário</b></td>';
        $html .= '<td><b>Tipo</b></td>';
        $html .= '<td><b>CID</b></td>';
        $html .= '<td><b>Tempo</b></td>';
        $html .= '<td><b>Unid Tempo</b></td>';
        $html .= '<td><b>Início</b></td>';
        $html .= '<td><b>Fim</b></td>';
        $html .= '<td><b>Comentários Adicionais:</b></td>';
        $html .= '</tr>';
        
        foreach ($resultado as $key => $value) {
            $html .= "<tr>";
            foreach ($value as $key2 => $value2) {
                if($key2=='Data Atend' || $key2=='Inicio' || $key2=='Fim' ) {
                   $html .= "<td>" . date("d/m/y",strtotime($value2)) . "</td>";
                } else {
                   $html .= "<td>$value2</td>";
                }
            }
            $html .= "</tr>";
        }
        // Configurações header para forçar o download
        header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header ("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT");
        header ("Cache-Control: no-cache, must-revalidate");
        header ("Pragma: no-cache");
        header ("Content-type: application/x-msexcel");
        header ("Content-Disposition: attachment; filename=\"{$arquivo}\"" );
        header ("Content-Description: PHP Generated Data" );
        // Envia o conteúdo do arquivo
        echo $html;
        exit;
    }