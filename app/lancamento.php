<?php

require_once("inc/config.php");
require_once("inc/api_functions.php");

$data = $_POST['data'] ?: '';
$data2 = $_POST['data2'] ?: '';

$rows = api_request('find_bills','POST',array('data' => $data, 'data2' => $data2, 'tipo' => 'LF'));

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
                    <td class="text-center" id="descricao_'.$row['id'].'">'.$row['descricao'].'</td>
                    <td class="text-center" id="valor_'.$row['id'].'">R$'.number_format($row['valor'], 2, ',', '.').'</td>
                    <td class="text-center" id="categoria_'.$row['id'].'">'.$row['categoria'].'</td>
                    <td class="text-center" id="data_'.$row['id'].'">'.$row['data'].'</td>
                    <td class="text-center">
                        <select name="selectTipo" id="selectTipo_'.$row['id'].'">
                            <option value="">Selecionar...</option>
                            <option value="RF">Receita Fixa</option>
                            <option value="RV">Receita Variável</option>
                            <option value="DF">Despesa Fixa</option>
                            <option value="DV">Despesa Variável</option>
                        </select>
                    </td>
                    <td class="text-center"><a onclick="return confirmar('.$row['id'].')">Confirmar</a></td>
                    <td class="text-center"><a onclick="return excluir('.$row['id'].')">Excluir</a></td>
                </tr>';
    }    
    $html .= '
            </tbody>
        </table>
    </div>';
}

echo json_encode($html);