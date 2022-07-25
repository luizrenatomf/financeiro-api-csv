<?php

$nomePagina = "Alterar registro";
require_once("inc/cabecalho.php");

$id = $_GET['id'];
$registro = api_request('find_bills','POST',array('id' => $id))['data'];

if($registro['status'] == 'ERROR') {
    die($registro['message']);
} else {
    $registro = $registro['results'];
    $registro['data'] = explode('/',$registro['data'])[2]."-".explode('/',$registro['data'])[1]."-".explode('/',$registro['data'])[0];
}

$tipos = ['RF' => 'Receita Fixa', 'RV' => 'Receita Variável', 'DF' => 'Despesa Fixa', 'DV' => 'Despesa Variável', 'LF' => 'Lançamento Futuro'];

$categorias = api_request('categories','POST',array('tipo' => $registro['tipo']))['data']['results'];

?>

<form action="" method="post" name="formulario" id="formulario">    
    <input type="hidden" name="categoria" id="categoriaPrev" value="<?=$registro['categoria'];?>">
    <div class="container">        
        <div class="row justify-content-center mb-4">
            <div class="form-group col-md-1">
                <label class="form-check-label" for="id">ID: </label>
                <input class="form-control" type="number" name="id" id="id" value="<?=$registro['id'];?>" disabled>
            </div>
            <div class="col-md-4">
                <label for="tipo">Tipo de despesa:</label>
                <select class="form-select" name="tipo" id="tipo" onchange="return categorias(this.value)">
                <?php 
                foreach($tipos as $chave => $valor) { 
                    if($chave == $registro['tipo']) {
                        echo "<option value=\"$chave\" selected>$valor</option>";
                    } else {
                        echo "<option value=\"$chave\">$valor</option>";
                    }
                } 
                ?>
                </select>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="categoria">Categoria:</label>
                    <select class="form-select" name="categoria" id="categoria">
                    <?php     
                        foreach($categorias as $categoria) {
                            if($categoria == $registro['categoria']) {
                                echo "<option value=\"$categoria\" selected>$categoria</option>";
                            } else {
                                echo "<option value=\"$categoria\">$categoria</option>";
                            }
                        }
                    ?>
                    </select>
                </div>        
            </div>
        </div>
        <div class="row justify-content-center mb-4">
            <div class="form-group col-md-4">
                <label class="form-check-label" for="descricao">Descricao: </label>
                <input class="form-control" type="text" name="descricao" id="descricao" maxlength="30" autocomplete="off" value="<?=$registro['descricao'];?>">
            </div>
            <div class="form-group col-md-2">
                <label class="form-check-label" for="valor">Valor: </label>
                <input class="form-control" type="number" format="currency" precision="2" name="valor" id="valor" maxlength="10" autocomplete="off" value="<?=$registro['valor'];?>">
            </div>
            <div class="form-group col-md-2">
                <label class="form-check-label" for="data">Data: </label>
                <input class="form-control" type="date" name="data" id="data" autocomplete="off" value="<?=$registro['data'];?>">
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="form-group col-md-1">
                <input class="btn btn-primary" type="button" value="&nbsp;Alterar&nbsp;" name="Alterar" onclick="javascript:valida_dados(formulario) && gravar()">
            </div>
            <div class="form-group col-md-1">
                <input class="btn btn-secondary" type="reset" value="&nbsp;Limpar&nbsp;" name="limpar" id="limpar">
            </div>
        </div>
    </div>
</form>
<hr>
<p align="center"><a href="index.php">Voltar</a></p>

<script type="text/javascript" language="javascript">
    
    function categorias(categoria) {
        $.ajax({
            type: "POST",
            dataType: "json",
            url: "roteamento.php",
            async: true,
            data: {
                acao: 'categorias',
                tipo: categoria
            },
            success: function(retorno) {
                if(retorno.data.status == 'SUCCESS') {
                    let categorias = retorno.data.results;
                    $("#categoria").empty();
                    categorias.forEach(function(categoria) {
                        if(categoria == $("#categoriaPrev").val()) {
                            $("<option value='"+categoria+"' selected>"+categoria+"</categoria>").appendTo("#categoria");
                        } else {
                            $("<option value='"+categoria+"'>"+categoria+"</categoria>").appendTo("#categoria");
                        }
                    });
                }
            },
        });
    }

    function valida_dados(formulario) {
        if($("#tipo").val() == "")
        {
            alert("Selecione um tipo de despesa válido.");
            formulario.tipo.focus();
            return false;
        }
        if($("#categoria").val() == "")
        {
            alert("Selecione uma categoria válida.");
            formulario.categoria.focus();
            return false;
        }
        if(formulario.descricao.value == "")
        {
            alert("Digite uma descrição válida.");
            formulario.descricao.focus();
            return false;
        }
        if(formulario.valor.value == "")
        {
            alert("Digite um valor válido.");
            formulario.valor.focus();
            return false;
        }
        if(formulario.data.value.length == "")
        {
            alert("Digite uma data válida.");
            formulario.ano.focus();
            return false;
        }
        return true;
    }

    function gravar() 
    {          
        let id = $("#id").val();
        let tipo = $("#tipo").val();
        let categoria = $("#categoria").val();
        let descricao = $("#descricao").val();
        let valor = $("#valor").val();
        let data = $("#data").val();
        // alert(id + ' ' + tipo + ' ' + categoria + ' ' + descricao + ' ' + valor + ' ' + data)
        $.ajax ({
            type: "POST",
            dataType: "json",
            url: "roteamento.php",
            async: true,
            data: {
                acao: 'atualizar',
                id: id,
                tipo: tipo,
                categoria: categoria,
                descricao: descricao,
                valor: valor,
                data: data
            },
            success: function(retorno) {
                if(retorno.data.status == 'SUCCESS') {
                    window.location.assign("index.php");
                }
                else {
                    $("#mensagem").append("<div class=\"alert alert-danger\" role=\"alert\"><h6 align=\"center\">Erro ao incluir.</h6></div>");
                    window.setTimeout(function(){$("#mensagem").empty()},3000)
                }
            },
            error: function(retorno) {
                console.log(retorno.responseText)
            }
        });
    }
</script>

<?php

require_once("inc/rodape.php");

?>