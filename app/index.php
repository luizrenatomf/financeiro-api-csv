<?php 

$nomePagina = "Home";
require_once("inc/cabecalho.php");

?>

<p align="center">Seja bem vindo! Escolha a opção desejada:</p>
<p align="center">
    <b>Incluir:</b><br>
    <a href="incluir.php?tipo=RF"><font size="4">Receitas fixas</font></a><br>
    <a href="incluir.php?tipo=RV"><font size="4">Receitas variáveis</font></a><br>
    <a href="incluir.php?tipo=DF"><font size="4">Despesas fixas</font></a><br>
    <a href="incluir.php?tipo=DV"><font size="4">Despesas variáveis</font></a><br>
    <a href="incluir.php?tipo=LF"><font size="4">Lançamentos futuros</font></a><br>
</p>
<p align="center">
    <b>Visualizar:</b><br>
    <a href="periodo.php?tipo=V"><font size="4">Registros</font></a><br>
    <a href="periodo.php?tipo=P"><font size="4">Planilha de gastos mensais</font></a><br>
    <a href="periodo.php?tipo=LF"><font size="4">Confirmar lançamentos</font></a><br>
</p>
<p align="center">
    <b>Excluir:</b><br>
    <a href="periodo.php?tipo=E"><font size="4">Excluir receitas e despesas</font></a><br>
</p>

<?php

require_once("inc/rodape.php");

?>