<?php

require_once("inc/config.php");
require_once("inc/api_functions.php");

$data = $_POST['data'] ?: '';
$data2 = $_POST['data2'] ?: '';
$tipos = ((isset($_POST['RF']) ? $_POST['RF'] . ',' : '') . 
          (isset($_POST['RV']) ? $_POST['RV'] . ',' : '') . 
          (isset($_POST['DF']) ? $_POST['DF'] . ',' : '') . 
          (isset($_POST['DV']) ? $_POST['DV'] : ''));

$rows = api_request('find_bills','POST',array('data' => $data, 'data2' => $data2, 'tipo' => $tipos));

if(empty($rows)) {
    die("Registros não encontrados.");
} else {
    $rows = $rows['data']['results'];

    $html = '
        <div class="container">
            <table class="table table-sm table-hover">
                <thead>
                    <tr>
                        <th scope="col" class="text-center">Descrição</th>
                        <th scope="col" class="text-center">Valor</th>
                        <th scope="col" class="text-center">Categoria</th>
                        <th scope="col" class="text-center">Referência</th>
                        <th scope="col" class="text-center">Tipo</th>
                    </tr>
                </thead>
                <tbody>';

    foreach($rows as $row) {
        $html .= '
            <tr>
                <td class="text-center">'.$row['descricao'].'</td>
                <td class="text-center">R$'.number_format($row['valor'], 2, ',', '.').'</td>
                <td class="text-center">'.$row['categoria'].'</td>
                <td class="text-center">'.$row['data'].'</td>
                <td class="text-center">'.$row['tipo'].'</td>
                <td class="text-center"><a onclick="return excluir('.$row['id'].');">Excluir</a></td>
            </tr>';
    }    
    $html .= '
            </tbody>
        </table>
    </div>';
}

echo json_encode($html);