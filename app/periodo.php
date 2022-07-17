<?php

$tipo = $_GET["tipo"];
if($tipo == "V")
{
    $nomePagina = "Visualização de registros";
    $form       = "gera_registro.php";
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

require_once("inc/cabecalho.php");

$ano = intval(date('Y'));
$mes = intval(date('M'));
$ultimoDia = date('t',mktime(0,0,$mes,'01',$ano));

?>

<form action="<?= $form; ?>" method="POST" name="formulario" id="formulario">
    <p align="center">Selecione o período:</p>
    <div class="container">
        <div class="row justify-content-center gx-1 mb-5">
            <div class="col-sm-3 col-xl-2">
                <input class="form-control" type="date" name="data" id="data" value="<?= date("Y-m-01"); ?>">
            </div>
            <div class="col-sm-3 col-xl-2">
                <input class="form-control" type="date" name="data2" id="data2" value="<?= date("Y-m-$ultimoDia"); ?>">
            </div>
            <button type="submit" class="btn btn-primary col-sm-2 col-xl-2" id="visualizar" onclick="return verificaCampos(formulario);">Visualizar</a>
        </div>
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
</script>

<?php

require_once("inc/rodape.php");

?>
