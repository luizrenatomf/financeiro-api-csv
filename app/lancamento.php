<?php

$nomePagina = "Confirmar lançamentos futuros";
require_once("inc/cabecalho.php");

$data = $_POST['data'] ?: '';
$data2 = $_POST['data2'] ?: '';

$rows = api_request('find_bills','POST',array('data' => $data, 'data2' => $data2, 'tipo' => 'LF'));

if(empty($rows)) {
    die("Registros não encontrados.");
} else {
    $rows = $rows['data']['results'];
?>

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
                <tbody>
                <?php                    
                    foreach($rows as $row) {
                ?>        
                    <tr>
                        <td class="text-center" id="descricao_<?=$row['id']?>"><?= $row['descricao']; ?></td>
                        <td class="text-center" id="valor_<?=$row['id']?>">R$<?= number_format($row['valor'], 2, ',', '.'); ?></td>
                        <td class="text-center" id="categoria_<?=$row['id']?>"><?= $row['categoria']; ?></td>
                        <td class="text-center" id="data_<?=$row['id']?>"><?= $row['data']; ?></td>
                        <td class="text-center">
                            <select name="selectTipo" id="selectTipo_<?=$row['id']?>">
                                <option value="">Selecionar...</option>
                                <option value="RF">Receita Fixa</option>
                                <option value="RV">Receita Variável</option>
                                <option value="DF">Despesa Fixa</option>
                                <option value="DV">Despesa Variável</option>
                            </select>
                        </td>
                        <td class="text-center"><a onclick="return confirmar(<?=$row['id'];?>)">Confirmar</a></td>
                        <td class="text-center"><a onclick="return excluir(<?=$row['id'];?>)">Excluir</a></td>
                    </tr>
                <?php                    
                    }    
                ?>
                </tbody>
            </table>
        </div>
<?php
}
?>        

<script>
    function excluir(id) {
        $.ajax ({
            type: "POST",
            dataType: "json",
            url: "roteamento.php",
            async: true,
            data: {
                id: id,
                acao: 'excluir'
            },
            success: function(retorno) {
                if(retorno.data.status == 'SUCCESS') {
                    window.location.reload();
                }
                else {
                    window.scrollTo(0,0);
                    $("#mensagem").append("<div class=\"alert alert-danger\" role=\"alert\"><h6 align=\"center\">Erro ao incluir.</h6></div>");
                    window.setTimeout(function(){$("#mensagem").empty()},3000)
                }
            },
        });
    }

    function confirmar(id) {
        let descricao = document.getElementById("descricao_" + id).innerHTML;
        let valor = document.getElementById("valor_" + id).innerHTML.replace('R$','');
        valor = valor.replace('.','');
        valor = Number(valor.replace(',','.'));
        let categoria = document.getElementById("categoria_" + id).innerHTML;
        let tipo = document.getElementById("selectTipo_" + id).value;
        let data = document.getElementById("data_" + id).innerHTML;

        // alert(descricao + ' ' + valor + ' ' + categoria + ' ' + tipo + ' ' + data)
        
        if(tipo === '') {
            alert('Não é possível confirmar uma lançamento sem definir seu novo tipo.');
            document.getElementById("selectTipo").focus();
            return false;
        }

        $.ajax({
            type: "POST",
            dataType: "json",
            url: "roteamento.php",
            async: true,
            data: {
                id: id,
                descricao: descricao,
                valor: valor,
                categoria: categoria,
                tipo: tipo,
                data: data,
                acao: 'atualizar'
            },
            success: function(retorno) {
                if(retorno.data.status == 'SUCCESS') {
                    window.location.reload();
                }
                else {
                    window.scrollTo(0,0);
                    $("#mensagem").append("<div class=\"alert alert-danger\" role=\"alert\"><h6 align=\"center\">Erro ao incluir.</h6></div>");
                    window.setTimeout(function(){$("#mensagem").empty()},3000)
                }
            },
        });
    }
</script>