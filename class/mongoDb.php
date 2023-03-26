<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of mogoDb
 *
 * @author marco
 */
class mongoDb {

    public $database;
    public $bulk;
    public $mongoAccess;

    public function __construct($_database  = 'coord') {
        $this->database = $_database;
        $this->mongoAccess = new MongoDB\Driver\Manager('mongodb://educ:aptN232ui@187.84.96.133:27017');
//        $this->mongoAccess = new MongoDB\Driver\Manager('mongodb://educ:aptN232ui@187.84.96.133:27017');
        $this->bulk = new MongoDB\Driver\BulkWrite();
    }

    public function insert($collection, $doc, $alert = NULL) {
        try {
            if (is_array($doc)) {
                $this->bulk->insert($doc);
                $this->mongoAccess->executeBulkWrite($this->database . '.' . $collection, $this->bulk);    # 'schooldb' is database and 'student' is collection.   

                $alert == 1 ? tool::alert('Salvo com Sucesso!') : '';
            }
        } catch (Exception $exc) {
            tool::alert("Algo errado não está certo!");
        }
    }

    public function query($collection, $filter, $option = []) {

        $query = new MongoDB\Driver\Query($filter, $option);
        $rows = $this->mongoAccess->executeQuery($this->database . '.' . $collection, $query);
        $array = $rows->toArray();

        return $array;
    }
/**
 * 
 * @param type $collection
 * @param type $criterion 
 * @param type $set dosdos em aray
 * @param type $option  ['multi' => false, 'upsert' => true]
 * @param type $alert
 */
    public function update($collection, $criterion, $set, $option = ['multi' => true, 'upsert' => true], $alert = NULL) {
        try {
            $array = $this->bulk->update($criterion, ['$set' => $set], $option);
            $this->mongoAccess->executeBulkWrite($this->database . '.' . $collection, $this->bulk);    # 'schooldb' is database and 'student' is collection.   
            $alert == 1 ? tool::alert('Atualizado com Sucesso!') : '';
        } catch (Exception $exc) {
            tool::alert("Algo errado não está certo!");
        }
    }

    public function delete($collection, $criterion, $option = ['limit' => 0], $alert = NULL) {
        try {
            $this->bulk->delete($criterion, $option);
            $result = $this->mongoAccess->executeBulkWrite($this->database . '.' . $collection, $this->bulk);

            $alert == 1 ? tool::alert('Apagado com Sucesso!') : '';
        } catch (Exception $exc) {
            tool::alert("Algo errado não está certo!");
        }
    }

    public function deleteId($collection, $id, $alert = NULL) {
        $id = ['_id' => new MongoDB\BSON\ObjectId($id)];
        $this->delete($collection, $id, ['limit' => 0], $alert);
    }

}

/**
 * 
$mongo->insert('usuarios', ['nome'=>'Anaela'], 1);
$array = $mongo->query('usuarios', ['nome' =>'Sandra'], ['limit' => 2]);
$array = $mongo->update('usuarios',['nome' =>'Sandra'], ['cpf'=> 2222222222222], NULL, 1);
$array = $mongo->deleteId('usuarios', '5af9c942d6fd0927d9081a93');
$array = $mongo->delete('usuarios', ['nome' => 'Anaelat']);
 * 
 * 
 */