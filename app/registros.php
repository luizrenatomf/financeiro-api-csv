<?php

require_once("inc/config.php");
require_once("inc/api_functions.php");

$data = $_POST['data'] ?: '';
$data2 = $_POST['data2'] ?: '';
$tipos = ((isset($_POST['RF']) ? $_POST['RF'] . ',' : '') . 
          (isset($_POST['RV']) ? $_POST['RV'] . ',' : '') . 
          (isset($_POST['DF']) ? $_POST['DF'] . ',' : '') . 
          (isset($_POST['DV']) ? $_POST['DV'] : ''));
$item = isset($_POST['item']) ? $_POST['item'] : '';

$rows = api_request('find_bills','POST',array('data' => $data, 'data2' => $data2, 'tipo' => $tipos, 'item' => $item));

if(empty($rows)) {
    die("Registros não encontrados.");
} else {
    $rows = $rows['data']['results'];

$html = '
<div class="container">
    <table class="table table-sm table-hover">
        <thead>
            <tr>
                <th scope="col" class="text-center" id="nome" onclick="javascript:ordenar(this.id)">Descrição</th>
                <th scope="col" class="text-center" id="valor" onclick="javascript:ordenar(this.id)">Valor</th>
                <th scope="col" class="text-center" id="categoria" onclick="javascript:ordenar(this.id)">Categoria</th>
                <th scope="col" class="text-center" id="data" onclick="javascript:ordenar(this.id)">Referência</th>
                <th scope="col" class="text-center" id="tipo" style="cursor:pointer" onclick="javascript:ordenar(this.id)">Tipo</th>
            </tr>
        </thead>
        <tbody> ';

        foreach($rows as $row) {
            $html .= '
            <tr style="cursor:pointer" onclick="javascript:alterar(' . $row["id"] . ')">
                <td class="text-center">' . $row["descricao"] . '</td>
                <td class="text-center">R$' . number_format($row["valor"], 2, ',', '.') . '</td>
                <td class="text-center">' . $row["categoria"] . '</td>
                <td class="text-center">' . $row["data"] . '</td>
                <td class="text-center">' . $row["tipo"] . '</td>
            </tr> ';
        }  

        $html .= '
        </tbody>
    </table>
</div>';
}

echo json_encode($html);