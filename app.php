<?php

    class Dashboard{

        public $dataInicio;
        public $dataFim;
        public $numeroVendas;
        public $totalVendas;
        public $clientesAtivos;
        public $clientesInativos;
        public $totalDespesas;
        public $contatoReclamacao;
        public $contatoElogio;
        public $contatoSugestao;


        public function __get($name)
        {
            return $this->$name;
        }

        public function __set($name, $value)
        {
            $this->$name = $value;
            return $this;
        }

    }
    //conexao banco de dados

    class Conexao{
        private $host = 'localhost';
        private $dbname = 'udemy_dashboard';
        private $user = 'root';
        private $pass = '';

        public function conectar(){
            try{
                $conexao = new PDO(
                    "mysql:host=$this->host;dbname=$this->dbname",
                    "$this->user",
                    "$this->pass"
                );

                //colocar toda aplicação no utf 8
                $conexao->exec('set charset set utf8');

                return $conexao;


            }catch(PDOException $e){
                echo '<p>'.$e->getMessage().'</p>';
            }
        }
    }

    class Bd{
        private $conexao;
        private $dashboard;

        public function __construct(Conexao $conexao, Dashboard $dashboard)
        {
            $this->conexao = $conexao->conectar();
            $this->dashboard = $dashboard;
        }

        public function getNumeroVendas(){
            $query = '
                SELECT
                    count(*) as numero_vendas
                FROM
                    tb_vendas
                WHERE
                    data_venda between :dataInicio and :dataFim
            ';

            $stmt = $this->conexao->prepare($query);
            $stmt->bindValue(':dataInicio', $this->dashboard->__get('dataInicio'));
            $stmt->bindValue(':dataFim', $this->dashboard->__get('dataFim'));
            $stmt->execute();

            return $stmt->fetch(PDO::FETCH_OBJ)->numero_vendas;

        }

        public function getTotalVendas(){
            $query = '
                SELECT
                    SUM(total) as total_vendas
                FROM
                    tb_vendas
                WHERE
                    data_venda between :dataInicio and :dataFim
            ';

            $stmt = $this->conexao->prepare($query);
            $stmt->bindValue(':dataInicio', $this->dashboard->__get('dataInicio'));
            $stmt->bindValue(':dataFim', $this->dashboard->__get('dataFim'));
            $stmt->execute();

            return $stmt->fetch(PDO::FETCH_OBJ)->total_vendas;

        }

        public function getTotalDespesas(){
            $query = '
                SELECT
                    SUM(total) as total_despesas
                FROM
                    tb_despesas
                WHERE
                    data_despesa between :dataInicio and :dataFim
            ';

            $stmt = $this->conexao->prepare($query);
            $stmt->bindValue(':dataInicio', $this->dashboard->__get('dataInicio'));
            $stmt->bindValue(':dataFim', $this->dashboard->__get('dataFim'));
            $stmt->execute();

            return $stmt->fetch(PDO::FETCH_OBJ)->total_despesas;

        }

        public function getClienteAtivos(){
            $query = '
                SELECT
                    count(*) as cliente_ativo
                FROM
                    tb_clientes
                WHERE
                    cliente_ativo = 1
            ';

        $stmt = $this->conexao->prepare($query);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_OBJ)->cliente_ativo;
        
        
        }

        public function getClienteInativos(){
            $query = '
                SELECT
                    count(*) as cliente_inativo
                FROM
                    tb_clientes
                WHERE
                    cliente_ativo = 0
            ';

        $stmt = $this->conexao->prepare($query);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_OBJ)->cliente_inativo;
        
        
        }

        public function getContatoRelamacao(){
            $query = '
                SELECT
                    count(*) as contato_reclamacao
                FROM
                    tb_contatos
                WHERE
                    tipo_contato = 1
            ';

        $stmt = $this->conexao->prepare($query);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_OBJ)->contato_reclamacao;
        
        
        }

        public function getContatoElogios(){
            $query = '
                SELECT
                    count(*) as contato_elogio
                FROM
                    tb_contatos
                WHERE
                    tipo_contato = 2
            ';

        $stmt = $this->conexao->prepare($query);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_OBJ)->contato_elogio;
        
        
        }

        public function getContatoSugestao(){
            $query = '
                SELECT
                    count(*) as contato_sugestao
                FROM
                    tb_contatos
                WHERE
                    tipo_contato = 3
            ';

        $stmt = $this->conexao->prepare($query);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_OBJ)->contato_sugestao;
        
        
        }


    }



    $dasboard = new Dashboard();
    $conexao = new Conexao();

    //pegando get enviado pelo ajax
    $competencia = explode('-', $_GET['competencia']);
    $ano = $competencia[0];
    $mes = $competencia[1];

    //função para descobrir os dias
    $diasDoMes = cal_days_in_month(CAL_GREGORIAN, $mes, $ano);


    $dasboard->__set('dataInicio', $ano.'-'.$mes.'-01');
    $dasboard->__set('dataFim', $ano.'-'.$mes.'-'.$diasDoMes);

    $bd = new Bd($conexao, $dasboard);

    $dasboard->__set('numeroVendas', $bd->getNumeroVendas());
    $dasboard->__set('totalVendas', $bd->getTotalVendas());
    

    $dasboard->__set('clientesAtivos', $bd->getClienteAtivos());
    $dasboard->__set('clientesInativos', $bd->getClienteInativos());


    $dasboard->__set('contatoReclamacao', $bd->getContatoRelamacao());
    $dasboard->__set('contatoElogio', $bd->getContatoElogios());
    $dasboard->__set('contatoSugestao', $bd->getContatoSugestao());

    $dasboard->__set('totalDespesas', $bd->getTotalDespesas());

    echo json_encode($dasboard);

    
    


?>