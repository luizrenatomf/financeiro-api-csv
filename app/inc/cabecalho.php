<?php 

require_once("inc/config.php");
require_once("inc/api_functions.php");

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

<!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
  <script defer src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
<!-- end Bootstrap -->   
  <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>

  <title>Controle de gastos mensais</title>
</head>
<body>
        
<nav class="navbar navbar-expand-lg navbar-light bg-light mb-3">
  <div class="container-fluid">
    <a class="navbar-brand" href="index.php">Controlin</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Incluir
          </a>
          <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
            <li><a class="dropdown-item" href="incluir.php?tipo=RF">Receitas fixas</a></li>
            <li><a class="dropdown-item" href="incluir.php?tipo=RV">Receitas variáveis</a></li>
            <li><a class="dropdown-item" href="incluir.php?tipo=DF">Despesas fixas</a></li>
            <li><a class="dropdown-item" href="incluir.php?tipo=DV">Despesas variáveis</a></li>
            <li><a class="dropdown-item" href="incluir.php?tipo=LF">Lançamentos futuros</a></li>
          </ul>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="periodo.php?tipo=V">Registros</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="periodo.php?tipo=P">Planilha</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="periodo.php?tipo=E">Excluir</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="contato.php">Contato</a>
        </li>
      </ul>
    </div>
  </div>
</nav>
<h1 align="center">Controle de gastos mensais</h1>
<h5 align="center" class="mb-3"><?= $nomePagina; ?></h5>
<hr>
<div id="mensagem"></div>