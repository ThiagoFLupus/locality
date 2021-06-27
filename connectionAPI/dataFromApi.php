<?php
namespace Locality\connectionAPI;

include 'ConnectionApi.php';
include __DIR__.'/../connectionDB/GetConnectionDB.php';
include __DIR__.'/../connectionDB/config.php';

//use Locality\connectionAPI\ConnectionApi;
use Locality\connectionDB\GetConnectionDB;
use const Locality\connectionDB\DATA_CONN_DB;

class getDataFromApi {
    private ConnectionApi $connectionApi;
    private GetConnectionDB $connectionDB;
    private $connDB;


    public function __construct(){
        $this-> connectionApi= new ConnectionApi();
        $this-> connectionDB= new GetConnectionDB(DATA_CONN_DB['host'], DATA_CONN_DB['database'], DATA_CONN_DB['port'], DATA_CONN_DB['user'], DATA_CONN_DB['password']);
        $this-> connDB= $this-> connectionDB-> getConnection();
    }

    public function getAndSaveCountries(){
        $url = 'https://servicodados.ibge.gov.br/api/v1/localidades/paises?orderBy=nome';

        $this-> connectionApi-> setUrl($url);
        $this-> connectionApi-> prepareCurl();
        $response= $this-> connectionApi-> exec();

        

        $i = 0;
        foreach($response as $abc){
            echo "##################################### País ". ++$i ." ############################################################\n";
            //print_r($abc);
            /*echo "id: " . $i . "\n";
            echo "m49: " . $abc-> id-> M49 . "\n";
            echo "iso_alpha_2: " . $abc-> id-> {"ISO-ALPHA-2"} . "\n";
            echo "iso_alpha_3: " . $abc-> id-> {"ISO-ALPHA-3"} . "\n";
            echo "nome: " . $abc-> nome . "\n";
            echo "sub_regiao_m49: " . $abc-> {"sub-regiao"}-> id->  M49 . "\n";
            echo "sub_regiao_nome: " . $abc-> {"sub-regiao"}-> nome . "\n";
            echo "regiao_m49: " . $abc-> {"sub-regiao"}-> regiao-> id-> M49 . "\n";
            echo "regiao_nome: " . $abc-> {"sub-regiao"}-> regiao-> nome . "\n";
            echo "\n";*/

            //Buscar pra saber se já tá no banco
            $sql= "select count(*) from countries where m49 = :m49 and iso_alpha_2 = :iso_alpha_2 and iso_alpha_3 = :iso_alpha_3 and name = :name";
            $sth= $this-> connDB-> prepare($sql);
            $sth->bindParam(':m49', $abc-> id-> M49);
            $sth->bindParam(':iso_alpha_2', $abc-> id-> {"ISO-ALPHA-2"});
            $sth->bindParam(':iso_alpha_3', $abc-> id-> {"ISO-ALPHA-3"});
            $sth->bindParam(':name', $abc-> nome);
            $sth-> execute();
            $sth->setFetchMode(\PDO::FETCH_ASSOC);
            $sth = $sth->fetchColumn();

            //se não, insert
            if($sth == 0){
                $sql= "insert into countries (m49, iso_alpha_2, iso_alpha_3, name, sub_region_m49, sub_region_name, region_m49, region_name) values (:m49, :iso_alpha_2, :iso_alpha_3, :name, :sub_region_m49, :sub_region_name, :region_m49, :region_name)";
                $sth= $this-> connDB-> prepare($sql);
                $sth->bindParam(':m49', $abc-> id-> M49);
                $sth->bindParam(':iso_alpha_2', $abc-> id-> {"ISO-ALPHA-2"});
                $sth->bindParam(':iso_alpha_3', $abc-> id-> {"ISO-ALPHA-3"});
                $sth->bindParam(':name', $abc-> nome);
                $sth->bindParam(':sub_region_m49', $abc-> {"sub-regiao"}-> id->  M49);
                $sth->bindParam(':sub_region_name', $abc-> {"sub-regiao"}-> nome);
                $sth->bindParam(':region_m49', $abc-> {"sub-regiao"}-> regiao-> id-> M49);
                $sth->bindParam(':region_name', $abc-> {"sub-regiao"}-> regiao-> nome);
                $sth-> execute();
                $sth->setFetchMode(\PDO::FETCH_ASSOC);
                $sth = $sth->fetchColumn();
            }

            print_r($sth);
            echo "\n";

        }
        
        echo "Total de itens encontrados : " . $i;
        echo "\n\n";
        
    }

    public function getAndSaveStates(){
        $url= 'https://servicodados.ibge.gov.br/api/v1/localidades/estados?orderBy=nome';

        $this-> connectionApi-> setUrl($url);
        $this-> connectionApi-> prepareCurl();
        $response= $this-> connectionApi-> exec();

        $i = 0;
        foreach($response as $abc){
            echo "##################################### Estado ". ++$i ." ############################################################\n";
            //print_r($abc);
            /*echo "id: " . $i . "\n";
            echo "id_on_ibge: " .$abc-> id. "\n";
            echo "uf: " .$abc-> sigla. "\n";
            echo "nome: " . $abc-> nome . "\n";
            echo "regiao_id_on_ibge: " . $abc-> regiao-> id. "\n";
            echo "regiao_sigla: " . $abc-> regiao-> sigla. "\n";
            echo "regiao_nome: " . $abc-> regiao-> nome . "\n";
            echo "\n\n";*/

            //Buscar pra saber se já tá no banco
            $sql= "select count(*) from states where id_on_ibge = :id_on_ibge and uf = :uf and name = :name";
            $sth= $this-> connDB-> prepare($sql);
            $sth->bindParam(':id_on_ibge', $abc-> id);
            $sth->bindParam(':uf', $abc-> sigla);
            $sth->bindParam(':name', $abc-> nome);
            $sth-> execute();
            $sth->setFetchMode(\PDO::FETCH_ASSOC);
            $sth = $sth->fetchColumn();

            //se não, insert
            if($sth == 0){
                $sql= "insert into states (id_on_ibge, uf, name, region_id_on_ibge, region_initials, region_name) values (:id_on_ibge, :uf, :name, :region_id_on_ibge, :region_initials, :region_name)";
                $sth= $this-> connDB-> prepare($sql);
                $sth->bindParam(':id_on_ibge', $abc-> id);
                $sth->bindParam(':uf', $abc-> sigla);
                $sth->bindParam(':name', $abc-> nome);
                $sth->bindParam(':region_id_on_ibge', $abc-> regiao-> id);
                $sth->bindParam(':region_initials', $abc-> regiao-> sigla);
                $sth->bindParam(':region_name', $abc-> regiao-> nome);
                $sth-> execute();
                $sth->setFetchMode(\PDO::FETCH_ASSOC);
                $sth = $sth->fetchColumn();
            }
        }

        print_r($sth);
        echo "\n";

        echo "Total de itens encontrados : " . $i;
        echo "\n\n";
    }

    //get states and loop for get Municípios
    public function getAndSaveCounties(){
        //buscar estados
        $sql= "select id, id_on_ibge from states";
        $sth= $this-> connDB-> prepare($sql);
        $sth-> execute();
        //$sth->setFetchMode(\PDO::FETCH_ASSOC);
        //$sth = $sth->fetchColumn();
        $sth= $sth-> fetchAll();

        $j= 0;
        if(sizeof($sth) > 0){
            foreach($sth as $state){
                $id_on_ibge= $state['id_on_ibge'];
                $url= "https://servicodados.ibge.gov.br/api/v1/localidades/estados/{$id_on_ibge}/municipios?orderBy=nome";

                $this-> connectionApi-> setUrl($url);
                $this-> connectionApi-> prepareCurl();
                //se não encontra, retorna array vazio
                $response= $this-> connectionApi-> exec();
                //echo "Resposta: \n";
                //print_r($response);
                //echo "\n";

                $i = 0;
                foreach($response as $abc){
                    echo "##################################### Municípios ". ++$i ." ############################################################\n";
                    //print_r($abc);
                    /*echo "id: " . $i . "\n";
                    echo "id_on_ibge: " .$abc-> id. "\n";
                    echo "nome: " . $abc-> nome . "\n";
                    echo "\n\n";*/

                    //Buscar pra saber se já tá no banco
                    $sql= "select count(*) from counties where id_on_ibge = :id_on_ibge and name = :name";
                    $sth= $this-> connDB-> prepare($sql);
                    $sth->bindParam(':id_on_ibge', $abc-> id);
                    $sth->bindParam(':name', $abc-> nome);
                    $sth-> execute();
                    $sth->setFetchMode(\PDO::FETCH_ASSOC);
                    $sth = $sth->fetchColumn();

                    //se não, insert
                    if($sth == 0){
                        $sql= "insert into counties (id_on_ibge, name, state_id, state_id_on_ibge) values (:id_on_ibge, :name, :state_id, :state_id_on_ibge)";
                        $sth= $this-> connDB-> prepare($sql);
                        $sth->bindParam(':id_on_ibge', $abc-> id);
                        $sth->bindParam(':name', $abc-> nome);
                        $sth->bindParam(':state_id', $state['id']);
                        $sth->bindParam(':state_id_on_ibge', $state['id_on_ibge']);
                        $sth-> execute();
                        $sth->setFetchMode(\PDO::FETCH_ASSOC);
                        $sth = $sth->fetchColumn();
                    }

                    $j++;
                }
            }

        }

        $url= "https://servicodados.ibge.gov.br/api/v1/localidades/estados/{$id_on_ibge}/municipios?orderBy=nome";

        $this-> connectionApi-> setUrl($url);
        $this-> connectionApi-> prepareCurl();
        //se não encontra, retorna array vazio
        //$response= $this-> connectionApi-> exec();
        //echo "Resposta: \n";
        //print_r($response);
        echo "\n";

        $i = 0;
        /*foreach($response as $abc){
            echo "##################################### Município ". ++$i ." ############################################################\n";
            //print_r($abc);
            echo "id: " . $i . "\n";
            echo "id_on_ibge: " .$abc-> id. "\n";
            echo "nome: " . $abc-> nome . "\n";
            echo "\n\n";

            //Buscar pra saber se já tá no banco
            $sql= "select count(*) from states where id_on_ibge = :id_on_ibge and uf = :uf and name = :name";
            $sth= $this-> connDB-> prepare($sql);
        }*/

        echo "Total de itens encontrados : " . $i;
        echo "\n\n";
        echo "Total de Municípios : " . $j;
        echo "\n\n";
    }
}

$fetchCountries= new getDataFromApi();
$fetchCountries-> getAndSaveCountries();
$fetchCountries-> getAndSaveStates();
$fetchCountries-> getAndSaveCounties();