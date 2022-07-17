<?php 

require_once("inc/config.php");
require_once("inc/api_functions.php");

if($_POST['acao'] === 'inserir') {

  $descricao = $_POST['descricao'];
  $valor = $_POST['valor'];
  $categoria = ($_POST['categoria_nova'] ?: ($_POST['categoria_existente']));
  $tipo = $_POST['tipo'];
  $dia = $_POST['dia'];
  $mes = $_POST['mes'] < 10 ? '0' . $_POST['mes'] : $_POST['mes'];
  $ano = $_POST['ano'];
  $data = $dia . '/' . $mes . '/' . $ano;
    
  $results = api_request('insert_bill','POST',array('descricao' => $descricao,
                                                    'valor' => $valor,
                                                    'categoria' => $categoria,
                                                    'tipo' => $tipo, 
                                                    'data' => $data
                                                  ));
  echo json_encode($results);
}

?>