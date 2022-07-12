<?php

class api_logic
{
    private $endpoint;
    private $params;

    public function __construct($endpoint, $params = null)
    {
        $this->endpoint = $endpoint;
        $this->params = $params;
    }

    public function endpoint_exists() 
    {
        return method_exists($this, $this->endpoint);
    }

    // endpoints

    public function status()
    {
        return [
            'status' => 'SUCCESS',
            'message' => 'API is running.',
            'results' => null
        ];
    }

    public function all_bills()
    {
        return [
            'status' => 'SUCCESS',
            'message' => '',
            'results' => $this->read_csv()
        ];      
    }

    public function insert_bill() 
    {
        $dados = $this->params;
        $dados = "\n" . $dados['id'] . "," . $dados['descricao'] . "," . $dados['valor'] . "," . $dados['tipo'] . "," . $dados['data'];
        $ficheiro = $this->open_csv('../teste.csv','a');
        fwrite($ficheiro,$dados);
        fclose($ficheiro);
        return [
            'status' => 'SUCCESS',
            'message' => 'Registro inserido com sucesso.',
            'results' => $this->params
        ];
    }

    public function find_bills()
    {
        $resultados = $this->find_csv();
        return [
            'status' => 'SUCCESS',
            'message' => '',
            'results' => $resultados
        ];
    }

    private function find_csv() 
    {
        $contas = $this->read_csv();        
        $resultados = array_filter($contas, function($conta) {
            $contaPrevia = null;
            $caracteristicas = ['descricao', 'valor', 'tipo', 'data'];

            foreach($caracteristicas as $c) {
                if(isset($this->params[$c])) {
                    if($this->params[$c] == $conta[$c]) {
                        $contaPrevia = $conta;
                    } else {
                        $contaPrevia = null;
                    }
                }
            }

            if(!is_null($contaPrevia)) {
                return $contaPrevia;
            } 
        }, ARRAY_FILTER_USE_BOTH);

        return $resultados;
    }

    public function data_filter_csv()
    {        
        if(isset($this->params['data2']) && is_null($this->params['data2'])) {
            $this->params['data2'] = $this->params['data'];
        }
    }

    private function read_csv()
    {
        $registros = array();
        $ficheiro = $this->open_csv('../teste.csv',"r");
        $cabecalho = fgetcsv($ficheiro);
        while(!feof($ficheiro)) {
            $linha = fgetcsv($ficheiro);
            if(!$linha) {
                continue;
            }
            $registros[] = array_combine($cabecalho,$linha);
        }
        fclose($ficheiro);  
        return $registros;
    }
    
    private function open_csv($arquivo, $metodo)
    {
        $ficheiro = fopen($arquivo,$metodo);
        if(!$ficheiro) {
            die('Erro ao abrir ficheiro.');
        }
        return $ficheiro;
    }
}