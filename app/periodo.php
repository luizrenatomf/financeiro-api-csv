<?php

$tipo = $_GET["tipo"];
if($tipo == "V")
{
    $nomePagina = "Visualização de registros";
    $form       = "registros.php";
}
else if($tipo == "E")
{
    $nomePagina = "Exclusão de registros";
    $form       = "excluir.php";
}
else if($tipo == "P")
{
    $nomePagina = "Planilha de gastos mensais";
    $form       = "planilha.php";
}
else if($tipo == "LF")
{
    $nomePagina = "Lançamentos futuros";
    $form       = "lancamento.php";
}

require_once("inc/cabecalho.php");

$ano = intval(date('Y'));
$mes = intval(date('M'));
$ultimoDia = date('t',mktime(0,0,$mes,'01',$ano));

?>

<form action="" method="POST" name="formulario" id="formulario">    
    <p align="center">Selecione o período:</p>
    <div class="container">
        <div class="row justify-content-center gx-1 mb-4">
            <div class="col-sm-3 col-xl-2">
                <input class="form-control" type="date" name="data" id="data" value="<?= date("Y-m-01"); ?>">
            </div>
            <div class="col-sm-3 col-xl-2">
                <input class="form-control" type="date" name="data2" id="data2" value="<?= date("Y-m-$ultimoDia"); ?>">
            </div>
            <button type="submit" class="btn btn-primary col-sm-2 col-xl-2" id="visualizar" onclick="return verificaCampos(formulario);">Visualizar</a>
        </div>
        <?php
        if($tipo == 'V' || $tipo == 'E') { ?>
        <div class="justify-content-center">
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="checkbox" value="RF" name="RF" id="RF">
                <label class="form-check-label" for="RF">Receitas Fixas</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="checkbox" value="RV" name="RV" id="RV">
                <label class="form-check-label" for="RV">Receitas Variáveis</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="checkbox" value="DF" name="DF" id="DF">
                <label class="form-check-label" for="DF">Despesas Fixas</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="checkbox" value="DV" name="DV" id="DV">
                <label class="form-check-label" for="DV">Despesas Variáveis</label>
            </div>
            <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" role="switch" id="selecionarTodos" onchange="javascript:selecionar()"/>
                <label class="form-check-label" for="selecionarTodos">Selecionar todos</label>
            </div>
        </div>  
        <?php } ?>
    </div>
    <input type="hidden" id="item" name="item" value="">
    <input type="hidden" name="url" id="url" value="<?=$form;?>">
</form>

<br><hr><br>

<div id="conteudo"></div>

<script src="./js/script.js"></script>

<?php

require_once("inc/rodape.php");

?>
