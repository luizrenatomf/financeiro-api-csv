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

<form action="<?= $form; ?>" method="POST" name="formulario" id="formulario">
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
</form>

<script>
    function verificaCampos(formulario) {
        if(formulario.data.value == '') {
            alert("Informe uma data válida.");
            formulario.data.focus();
            return false;
        }
        if(formulario.data.value == '') {
            alert("Informe uma data válida.");
            formulario.data.focus();
            return false;
        }
        return true;
    }

    function selecionar() {
        inputRf = document.getElementById("RF");
        inputRv = document.getElementById("RV");
        inputDf = document.getElementById("DF");
        inputDv = document.getElementById("DV");

        if(inputRf.hasAttribute('checked') || inputRv.hasAttribute('checked') || inputDf.hasAttribute('checked') || inputDv.hasAttribute('checked')) {
            inputRf.removeAttribute('checked');                        
            inputRv.removeAttribute('checked');                        
            inputDf.removeAttribute('checked');                        
            inputDv.removeAttribute('checked');                        
        } else {
            inputRf.setAttribute('checked',true);
            inputRv.setAttribute('checked',true);
            inputDf.setAttribute('checked',true);
            inputDv.setAttribute('checked',true);
        }
    }
</script>

<?php

require_once("inc/rodape.php");

?>
