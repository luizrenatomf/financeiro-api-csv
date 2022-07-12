<?php

function write_csv(&$data,$arquivo,$dados) {
    $dados = "\n$dados";
    $ficheiro = open_csv($arquivo,'a');
    fwrite($ficheiro,$dados);
    fclose($ficheiro);
    define_response($data,'Registro inserido com sucesso.');
}

function read_csv(&$data,$arquivo)
{
    $ficheiro = open_csv($arquivo,"r");
    $cabecalho = fgetcsv($ficheiro);
    while(!feof($ficheiro)) {
        $linha = fgetcsv($ficheiro);
        if(!$linha) {
            continue;
        }
        $registros[] = array_combine($cabecalho,$linha);
    }
    fclose($ficheiro);
    define_response($data,$registros);
}

function open_csv($arquivo, $metodo)
{
    $ficheiro = fopen($arquivo,$metodo);
    if(!$ficheiro) {
        die('Erro ao abrir ficheiro.');
    }
    return $ficheiro;
}

function api_status(&$data)
{
    define_response($data, 'API is running...');
}

function define_response(&$data, $value)
{
    $data['status'] = 'SUCCESS';
    $data['data'] = $value;
}

function response($data_response)
{
    header("Content-Type:application/json");
    echo json_encode($data_response);
}