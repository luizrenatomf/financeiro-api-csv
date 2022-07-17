<?php

$tipo = $_GET["tipo"];
if($tipo == "RF")
{
    $titulo = "RECEITAS FIXAS";
}
else if($tipo == "RV")
{
    $titulo = "RECEITAS VARIÁVEIS";
}
else if($tipo == "DF")
{
    $titulo = "DESPESAS FIXAS";
}
else if($tipo == "DV")
{
    $titulo = "DESPESAS VARIÁVEIS";
}

$nomePagina = "Inclusão de $titulo";
require_once("inc/cabecalho.php");

$categorias = api_request('categories','POST',array('tipo' => $tipo));

?>

<form action="" method="post" name="formulario" id="formulario">
    <input type="hidden" name="tipo" value="<?= $tipo; ?>" checked>
    <input type="hidden" name="acao" value="inserir" checked>
    <div class="container">
        <div class="row justify-content-center mb-4">
            <div class="form-group col-md-4">
                <label class="form-check-label" for="descricao">Descricao: </label>
                <input class="form-control" type="text" name="descricao" id="descricao" maxlength="30" autocomplete="off">
            </div>
            <div class="form-group col-md-2">
                <label class="form-check-label" for="valor">Valor: </label>
                <input class="form-control" type="text" name="valor" id="valor" maxlength="10" autocomplete="off">
            </div>
        </div>

        <div class="row justify-content-center mb-4">
            <div class="col-md-4">
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="categoria" id="categoria_n" value="nova" checked>
                    <label class="form-check-label" for="categoria_n">Nova: </label>
                </div>
                <input class="form-control" type="text" name="categoria_nova" onKeyDown="javascript:formulario.categoria[0].checked=true" autocomplete="off">
            </div>
            <div class="col-md-4">
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="categoria" id="categoria_e" value="existente">
                    <label class="form-check-label" for="categoria_e">Existente: </label>
                </div>
                <div class="form-group">
                    <select class="form-select" name="categoria_existente" onChange="javascript:formulario.categoria[1].checked=true">
                    <?php     
                        foreach($categorias['data']['results'] as $categoria) {
                            echo "<option value=\"$categoria\">$categoria</option>";
                        }
                    ?>
                    </select>
                </div>        
            </div>
        </div>
        <div class="row justify-content-center mb-5">
            <div class="form-group col-md-1">
                <label class="form-check-label" for="dia">Dia: </label>
                <input class="form-control" type="text" name="dia" id="dia" value="<?= date("d",time()); ?>">
            </div>
            <div class="form-group col-md-2">
                <label class="form-check-label" for="mes">Mês: </label>
                <select class="form-select" name="mes" id="mes">
                <?php
                    $meses = array("Jan","Fev","Mar","Abr","Mai","Jun","Jul","Ago","Set","Out","Nov","Dez");
                    foreach($meses as $key => $mes)
                    {
                        $key++;
                        if($key == date("m",time()))
                        {
                            echo "<option value=\"$key\" selected>$mes</option>";
                        }
                        else
                        {
                            echo "<option value=\"$key\">$mes</option>";
                        }
                    }
                ?>
                </select>
            </div>
            <div class="form-group col-md-2">
                <label class="form-check-label" for="ano">Ano: </label>
                <input class="form-control" type="text" name="ano" id="ano" value="<?= date("Y",time()); ?>">
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="form-group col-md-1">
                <input class="btn btn-primary" type="button" value="&nbsp;Enviar&nbsp;" name="enviar" onclick="return valida_dados(formulario);">
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
    function valida_dados(formulario) {
        if(formulario.descricao.value == "")
        {
            alert("Você não digitou a descrição.");
            formulario.descricao.focus();
            return false;
        }
        if(formulario.valor.value == "")
        {
            alert("Você não digitou o valor.");
            formulario.valor.focus();
            return false;
        }
        if(formulario.categoria_nova.value == "" && 
            formulario.categoria[0].checked == true)
        {
            alert("Você não digitou a categoria.");
            formulario.categoria_nova.focus();
            return false;
        }
        if(formulario.dia.value == "")
        {
            alert("Digite um dia válido.");
            formulario.dia.focus();
            return false;
        }
        if(formulario.mes.value == "")
        {
            alert("Selecione um mês válido.");
            formulario.mes.focus();
            return false;
        }
        if(formulario.ano.value.length < 4)
        {
            alert("Digite o ano com quatro dígitos.");
            formulario.ano.focus();
            return false;
        }
        gravar();
    }

    function gravar() 
    {  
        var dados = $("#formulario").serialize();
        $.ajax ({
            type: "POST",
            dataType: "json",
            url: "roteamento.php",
            async: true,
            data: dados,
            success: function(retorno) {
                if(retorno.data.status == 'SUCCESS') {
                    $("#limpar").click();
                    $("#mensagem").append("<div class=\"alert alert-success\" role=\"alert\"><h6 align=\"center\">"+retorno.data.message+"</h6></div>");
                    window.setTimeout(function(){$("#mensagem").empty()},3000)
                }
                else {
                    $("#mensagem").append("<div class=\"alert alert-danger\" role=\"alert\"><h6 align=\"center\">Erro ao incluir.</h6></div>");
                    window.setTimeout(function(){$("#mensagem").empty()},3000)
                }
            },
        });
    }
</script>

<?php

require_once("inc/rodape.php");

?>