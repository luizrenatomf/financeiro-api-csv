<?php

$nomePagina = "Planilha de gastos";
require_once("inc/config.php");
require_once("inc/api_functions.php");

$array_datas = $RF = $RV = $DF = $DV = array();

$meses = array("Jan","Fev","Mar","Abr","Mai","Jun","Jul","Ago","Set","Out","Nov","Dez");

$data = $_POST['data'] ?: '';
$data2 = $_POST['data2'] ?: '';

$rows = api_request('find_bills','GET',array('data' => $data, 'data2' => $data2))['data']['results'];

foreach($rows as $row)
{
    $descricao = $row["categoria"];
    $tipo      = $row["tipo"];
    $data      = $row["data"];
    $valor     = $row["valor"];

    $aux = explode("/",$data);
    $ano = $aux[2];
    $mes = $aux[1];
    $dia = $aux[0];
    $numero_mes = $mes - 1;
    $data = $meses[$numero_mes] . "-" . $ano;
    if(!in_array($data,$array_datas))
    {
        $array_datas[] = $data;
    }
    if($tipo == "RF")
    {
        if(!in_array($descricao, $RF))
        {
            $RF[] = $descricao;
        }
        if(isset($receitas_fixas[$descricao][$data]))
        {
            $receitas_fixas[$descricao][$data] += $valor;
        }
        else
        {
            $receitas_fixas[$descricao][$data] = $valor;
        }
        if(isset($total_receitas[$data]))
        {
            $total_receitas[$data] += $valor;
        }
        else
        {
            $total_receitas[$data] = $valor;
        }
    }
    elseif($tipo == "RV")
    {
        if(!in_array($descricao,$RV))
        {
            $RV[] = $descricao;
        }
        if(isset($receitas_variaveis[$descricao][$data]))
        {
            $receitas_variaveis[$descricao][$data] += $valor;
        }
        else
        {
            $receitas_variaveis[$descricao][$data] = $valor;
        }
        if(isset($total_receitas[$data]))
        {
            $total_receitas[$data] += $valor;
        }
        else
        {
            $total_receitas[$data] = $valor;
        }
    }
    elseif($tipo == "DF")
    {
        if(!in_array($descricao, $DF))
        {
            $DF[] = $descricao;
        }
        if(isset($despesas_fixas[$descricao][$data]))
        {
            $despesas_fixas[$descricao][$data] += $valor;
        }
        else
        {
            $despesas_fixas[$descricao][$data] = $valor;
        }
        if(isset($total_despesas[$data]))
        {
            $total_despesas[$data] += $valor;
        }
        else
        {
            $total_despesas[$data] = $valor;
        }
    }
    elseif($tipo == "DV")
    {
        if(!in_array($descricao,$DV))
        {
            $DV[] = $descricao;
        }
        if(isset($despesas_variaveis[$descricao][$data]))
        {
            $despesas_variaveis[$descricao][$data] += $valor;
        }
        else
        {
            $despesas_variaveis[$descricao][$data] = $valor;
        }
        if(isset($total_despesas[$data]))
        {
            $total_despesas[$data] += $valor;
        }
        else
        {
            $total_despesas[$data] = $valor;
        }
    }
}    

$numero_colunas = sizeof($array_datas);
$colunas_html = $numero_colunas + 1;

$html = "
<div align=\"table-responsive\">
    <table class=\"table table-sm table-hover\" border=\"1\">
        <thead>
            <tr>
                <td></td>
";                

foreach($array_datas as $data)
{
    $html .= "<td align=\"center\" width=\"100\"><b><font color=\"000080\">$data</font></b></td>";
}

$html .= " 
            </tr>
        </thead>    
        <tr>
            <td colspan=\"<?= $colunas_html; ?>\" bgcolor=\"#F5F5F5\">
                <b>RECEITAS FIXAS</b>
            </td>
        </tr>
";        

for($i = 0; $i < sizeof($RF); $i++)
{
    $descricao = $RF[$i];
    $html .= "<tr><td width=\"142\">$descricao</td>";
    for($j = 0; $j < $numero_colunas; $j++)
    {
        $data = $array_datas[$j];
        if(isset($receitas_fixas[$descricao][$data]))
        {
            $valor = $receitas_fixas[$descricao][$data];
            $html .= "<td align=\"center\" width=\"100\"> " . number_format($valor, 2, ',', '.') . "</td>";
        }
        else
        {
            $html .= "<td align=\"center\" width=\"100\">&nbsp;&nbsp;</td>";
        }
    }
    $html .= "</tr>";
}

$html .= "
        <tr>
            <td colspan=\"<?= $colunas_html; ?>\" bgcolor=\"#F5F5F5\">
                <b>RECEITAS VARIÁVEIS</b>
            </td>
        </tr>
";

for($i = 0; $i < sizeof($RV); $i++)
{
    $descricao = $RV[$i];
    $html .= "<tr><td width=\"142\">$descricao</td>";
    for($j = 0; $j < $numero_colunas; $j++)
    {
        $data = $array_datas[$j];
        if(isset($receitas_variaveis[$descricao][$data]))
        {
            $valor = $receitas_variaveis[$descricao][$data];
            $html .= "<td align=\"center\" width=\"100\"> " . number_format($valor, 2, ',', '.') . "</td>";
        }
        else
        {
            $html .= "<td align=\"center\" width=\"100\">&nbsp;&nbsp;</td>";
        }
    }
    $html .= "</tr>";
}

$html .= "
        <tr>
            <td width=\"142\" bgcolor=\"#D7FFFF\"><b>Total Receitas:</b></td>
";

foreach($array_datas as $data)
{
    if(isset($total_receitas[$data]))
    {
        $total = $total_receitas[$data];
    }
    else
    {
        $total = 0;
    }
    $html .= "<td align=\"center\" bgcolor=\"#D7FFFF\" width=\"100\"><b> " . number_format($total, 2, ',', '.') . "</b></td>";
}

$html .= "
        </tr>
        <tr>
            <td colspan=\"<?= $colunas_html; ?>\" bgcolor=\"#F5F5F5\"><b>DESPESAS FIXAS</b></td>
        </tr>
";        

for($i = 0; $i < sizeof($DF); $i++)
{
    $descricao = $DF[$i];
    $html .= "<tr><td width=\"142\">$descricao</td>";
    for($j = 0; $j < $numero_colunas; $j++)
    {
        $data = $array_datas[$j];
        if(isset($despesas_fixas[$descricao][$data]))
        {
            $valor = $despesas_fixas[$descricao][$data];
            $html .= "<td align=\"center\" width=\"100\"> " . number_format($valor, 2, ',', '.') . "</td>";
        }
        else
        {
            $html .= "<td align=\"center\" width=\"100\">&nbsp;&nbsp;</td>";
        }
    }
    $html .= "</tr>";
}

$html .= "
        <tr>
            <td colspan=\"<?= $colunas_html; ?>\" bgcolor=\"#F5F5F5\"><b>DESPESAS VARIÁVEIS</b></td>
        </tr>
";

for($i = 0; $i < sizeof($DV); $i++)
{
    $descricao = $DV[$i];                        
    $html .= "<tr><td width=\"142\">$descricao</td>";
    for($j = 0; $j < $numero_colunas; $j++)
    {
        $data = $array_datas[$j];
        if(isset($despesas_variaveis[$descricao][$data]))
        {
            $valor = $despesas_variaveis[$descricao][$data];
            $html .= "<td align=\"center\" width=\"100\"> " . number_format($valor, 2, ',', '.') . "</td>";
        }
        else
        {
            $html .= "<td align=\"center\" width=\"100\">&nbsp;&nbsp;</td>";
        }
    }
    $html .= "</tr>";
}

$html .= "
        <tr>
            <td width=\"142\" bgcolor=\"#D7FFFF\"><b>Total Despesas:</b></td>
";

foreach($array_datas as $data)
{
    if(isset($total_despesas[$data]))
    {
        $total = $total_despesas[$data];
    }
    else
    {
        $total = 0;
    }
    $html .= "<td align=\"center\" bgcolor=\"#D7FFFF\" width=\"100\"><b> " . number_format($total, 2, ',', '.') . "</b></td>";
}

$html .= "  
        </tr>             
        <tr>
            <td width=\"142\" bgcolor=\"#CCFFCC\"><b>SALDO</b></td>
";

foreach($array_datas as $data)
{
    $saldo = 0;
    if(isset($total_receitas[$data]))
    {
        $saldo += $total_receitas[$data];
    }
    if(isset($total_despesas[$data]))
    {
        $saldo -= $total_despesas[$data];
    }
    if($saldo < 0)
    {
        $cor = "#FF0000";
    }
    else
    {
        $cor = "#0000FF";
    }
    $html .= "<td align=\"center\" bgcolor=\"#CFFCC\" width=\"100\"><font colo=\"$cor\"><b> " . number_format($saldo, 2, ',', '.') . "</b></font></td>";
}

$html .= "
            </tr>
        </table>
    </div>
";

echo $html;

?>

<a href="periodo.php?tipo=P">Voltar</a>