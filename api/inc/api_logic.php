<?php

class api_logic
{
    private $endpoint;
    private $params;
    private $arquivo = 'C:/Users/luizr/Documents/contas.csv';

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

    public function update_bill()
    {
        $this->update_csv();
        return [
            'status' => 'SUCCESS',
            'message' => 'Registro atualizado com sucesso.',
            'results' => ''
        ];
    }

    public function insert_bill() 
    {
        $this->insert_csv();
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
            'message' => 'Registro excluído com sucesso.',
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
        $contas = $this->read_csv();
        if(isset($this->params['tipo'])) {
            $categorias = array();
            foreach($contas as $conta) {
                if($this->params['tipo'] == $conta['tipo']) {
                    if(!in_array($conta['categoria'],$categorias)) {
                        $categorias[] = $conta['categoria'];
                    }
                }
            }
        } else {
            $categorias = ['RF' => array(),'RV' => array(),'DF' => array(),'DV' => array(),'LF' => array()];
            foreach($contas as $conta) {
                if(!in_array($conta['categoria'],$categorias[$conta['tipo']])) {
                    $categorias[$conta['tipo']][] = $conta['categoria'];
                }
            }
        }
        return [
            'status' => 'SUCCESS',
            'message' => '',
            'results' => $categorias
        ];
    }

    private function update_csv()
    {
        $ficheiro = $this->open_csv($this->arquivo,'r');
        while($linha = fgetcsv($ficheiro)) {
            if(!$linha) {
                continue;
            }
            if($linha[0] == $this->params['id']) {
                if(isset($this->params['descricao'])) 
                    $linha[1] = $this->params['descricao'];
                if(isset($this->params['valor'])) 
                    $linha[2] = $this->params['valor'];
                if(isset($this->params['categoria'])) 
                    $linha[3] = $this->params['categoria'];
                if(isset($this->params['tipo'])) 
                    $linha[4] = $this->params['tipo'];
                if(isset($this->params['data']))
                    $linha[5] = $this->params['data'];                
                $registros[] = $linha;
            } else {
                $registros[] = $linha;
            }           
        }
        fclose($ficheiro);
        $ficheiro = $this->open_csv($this->arquivo,'w');
        foreach($registros as $registro) {
            fputcsv($ficheiro,$registro);
        }
        fclose($ficheiro);
    }

    private function find_csv() 
    {
        $contas = $this->read_csv();   
        
        if(isset($this->params['id'])) {
            $conta = $this->id_filter_csv();
            return $conta;
        }

        if(isset($this->params['data'])) {
            $this->data_filter_csv($contas);   
        }        

        $resultados = array_filter($contas, function($conta) {   
            $tipos = explode(',',$this->params['tipo']);   
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
                if(!in_array($conta['tipo'],$tipos))
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

    private function id_filter_csv()
    {
        $contas = $this->read_csv();
        foreach($contas as $conta) {
            if($conta['id'] == $this->params['id']) {
                return $conta;
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

    private function delete_csv()
    {
        $ficheiro = $this->open_csv($this->arquivo,'r');
        while($linha = fgetcsv($ficheiro)) {
            if(!$linha) {
                continue;
            }
            if($linha[0] == $this->params['id']) {
                unset($linha);
            } else {
                $registros[] = $linha;
            }           
        }
        fclose($ficheiro);
        $ficheiro = $this->open_csv($this->arquivo,'w');
        foreach($registros as $registro) {
            fputcsv($ficheiro,$registro);
        }
        fclose($ficheiro);
    }

    private function insert_csv()
    {
        $dados = array_merge(array('id' => $this->id()),array_slice($this->params,1));
        $ficheiro = $this->open_csv($this->arquivo,'a');
        fputcsv($ficheiro,$dados);
        fclose($ficheiro);
    }
    
    private function read_csv()
    {
        $ficheiro = $this->open_csv($this->arquivo,"r");
        $cabecalho = fgetcsv($ficheiro);
        while($linha = fgetcsv($ficheiro)) {
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
            return [
                'status' => 'ERROR',
                'message' => 'Erro ao abrir o ficheiro.',
                'results' => ''
            ];    
        }
        return $ficheiro;
    }
}