<?php 

require_once("inc/config.php");
require_once("inc/api_functions.php");

if($_POST['acao'] === 'inserir') {

  $descricao = $_POST['descricao'];
  $valor = $_POST['valor'];
  $categoria = ($_POST['categoria_nova'] ?: ($_POST['categoria_existente']));
  $tipo = $_POST['tipo'];
  $data = explode('-',$_POST['data'])[2]."/".explode('-',$_POST['data'])[1]."/".explode('-',$_POST['data'])[0];
    
  $results = api_request('insert_bill','POST',array('descricao' => $descricao,
                                                    'valor' => $valor,
                                                    'categoria' => $categoria,
                                                    'tipo' => $tipo, 
                                                    'data' => $data
                                                  ));
  echo json_encode($results);
}

if($_POST['acao'] === 'excluir') {

  $id = $_POST['id'];
  $results = api_request('delete_bill','POST',array('id' => $id));
  echo json_encode($results);
}

if($_POST['acao'] === 'atualizar') {

  $id = $_POST['id'];
  $descricao = $_POST['descricao'];
  $valor = $_POST['valor'];
  $categoria = $_POST['categoria'];
  $tipo = $_POST['tipo'];
  $data = $_POST['data'];

  if(strpos($data,'-') > 0) {
    $data = explode('-',$data)[2]."/".explode('-',$data)[1]."/".explode('-',$data)[0];
  }

  $results = api_request('update_bill','POST',array('id' => $id,
                                                    'descricao' => $descricao,
                                                    'valor' => $valor,
                                                    'categoria' => $categoria,
                                                    'tipo' => $tipo, 
                                                    'data' => $data
                                                  ));
  echo json_encode($results);
}

if($_POST['acao'] === 'categorias') {

  $tipo = $_POST['tipo'];
  $results = api_request('categories','POST',array('tipo' => $tipo));
  echo json_encode($results);
}

?>