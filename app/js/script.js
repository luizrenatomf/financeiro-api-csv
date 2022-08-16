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

document.getElementById("visualizar").addEventListener('click',function(evento) {    
    evento.preventDefault();    
    var dados = $("#formulario").serialize();
    var url = $("#url").val();
    $.ajax ({
        type: "POST",
        dataType: "json",
        url: url,
        async: true,
        data: dados,
        success: function(retorno) {
            $("#conteudo").html('');
            $("#conteudo").append(retorno);
        },
        error: function(jqXHR, textStatus, errorThrown, retorno) {
            $("#conteudo").append(retorno + '<br>' + textStatus);
        }
    });
});

function alterar(id) {
   window.location.assign("alterar.php?id="+id);
}

function ordenar(item) {
    var itens = $("#item").val() != '' ? $("#item").val().split(',') : [];
    if(itens.indexOf(item) > -1) {            
        itens.splice($.inArray(item,itens),1);
    } else {
        itens.push(item);
    }
    $("#item").val(itens.toString());

    var dados = $("#formulario").serialize();
    $.ajax ({
        type: "POST",
        dataType: "json",
        url: "registros.php",
        async: true,
        data: dados,
        success: function(retorno) {
            $("#conteudo").html('');
            $("#conteudo").append(retorno);
        },
        error: function(jqXHR, textStatus, errorThrown, retorno) {
            $("#conteudo").append(retorno + '<br>' + textStatus);
        }
    });
}

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
                window.scrollTo(0,0);
                $("#mensagem").append("<div class=\"alert alert-success\" role=\"alert\"><h6 align=\"center\">Registro excluído com sucesso.</h6></div>");
                window.setTimeout(function(){$("#mensagem").empty()},3000);
                visualizar.click();
            }
            else {
                window.scrollTo(0,0);
                $("#mensagem").append("<div class=\"alert alert-danger\" role=\"alert\"><h6 align=\"center\">Erro ao excluir.</h6></div>");
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
                window.scrollTo(0,0);
                $("#mensagem").append("<div class=\"alert alert-success\" role=\"alert\"><h6 align=\"center\">Registro lançado com sucesso.</h6></div>");
                window.setTimeout(function(){$("#mensagem").empty()},3000);
                visualizar.click();
            }
            else {
                window.scrollTo(0,0);
                $("#mensagem").append("<div class=\"alert alert-danger\" role=\"alert\"><h6 align=\"center\">Erro ao incluir.</h6></div>");
                window.setTimeout(function(){$("#mensagem").empty()},3000)
                visualizar.click();
            }
        },
    });
}