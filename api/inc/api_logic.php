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
        $dados = "\n" . $this->id() . "," . $dados['descricao'] . "," . $dados['valor'] . "," . $dados['categoria'] . "," . $dados['tipo'] . "," . $dados['data'];
        $ficheiro = $this->open_csv('../teste.csv','a');
        fwrite($ficheiro,$dados);
        fclose($ficheiro);
        return [
            'status' => 'SUCCESS',
            'message' => 'Registro inserido com sucesso.',
            'results' => $this->params
        ];
    }

    public function delete_bill()
    {
        $this->delete_csv();
        return [
            'status' => 'SUCCESS',
            'message' => 'Registro deletado com sucesso.',
            'results' => ''
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

    public function categories()
    {
        $categorias = array();
        $contas = $this->read_csv();
        foreach($contas as $conta) {
            if(isset($this->params['tipo']) && $this->params['tipo'] == $conta['tipo']) {
                if(!in_array($conta['categoria'],$categorias)) {
                    $categorias[] = $conta['categoria'];
                }
            }
        }
        return [
            'status' => 'SUCCESS',
            'message' => '',
            'results' => $categorias
        ];
    }

    private function find_csv() 
    {
        $contas = $this->read_csv();     
        if(isset($this->params['data'])) {
            $this->data_filter_csv($contas);   
        }        
        $resultados = array_filter($contas, function($conta) {            
            if(isset($this->params['descricao']) && isset($conta))
                if(!($conta['descricao'] === $this->params['descricao'])) 
                    unset($conta);
            
            if(isset($this->params['valor']) && isset($conta))
                if(!($conta['valor'] === $this->params['valor']))
                    unset($conta);
            
            if(isset($this->params['categoria']) && isset($conta))
                if(!($conta['categoria']) === $this->params['categoria'])
                    unset($conta);

            if(isset($this->params['tipo']) && isset($conta))
                if(!($conta['tipo'] === $this->params['tipo']))
                    unset($conta);
            
            if(isset($conta))
                return $conta;
        }, ARRAY_FILTER_USE_BOTH);
        return $resultados;
    }

    private function data_filter_csv(&$contas)
    {        
        $contas = $this->read_csv();     
        if(!isset($this->params['data']) || $this->params['data'] == '') {
            die('Data inválida');
        }
        $data = new DateTime(str_replace('/','-',$this->params['data']));
        
        if(!isset($this->params['data2']) || $this->params['data2'] == '') {
            $this->params['data2'] = $this->params['data'];
        }
        $data2 = new DateTime(str_replace('/','-',$this->params['data2']));

        if($data2 < $data) {
            $dataTemp = $data;
            $data = $data2;
            $data2 = $dataTemp;
            unset($dataTemp);
        }

        foreach($contas as $key => $conta) {
            $dataConta = new DateTime(str_replace('/','-',$conta['data']));
            if(!($dataConta >= $data && $dataConta <= $data2)) {                
                unset($contas[$key]);
            }
        }
    }

    private function id()
    {
        $contas = $this->read_csv();
        $conta = array_slice($contas,-1,1);
        $id = reset($conta)['id'] + 1;
        return $id;
    }

    public function delete_csv()
    {
        $ficheiro = $this->open_csv('../teste.csv','r+');
        while(!feof($ficheiro)) {
            $linha = fgetcsv($ficheiro);
            if(!$linha) {
                continue;
            }
            if($linha[0] == $this->params['id']) {
                unset($linha);
            }
        }
        fclose($ficheiro);
        
        // reorganizar?
        
        return;
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