<?php

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
class mongoCrude {

    public $database;
    public $bulk;
    public $mongoAccess;

    public function __construct($_database = null) {
        if (empty($_database)) {
            $this->database = MONGO_DB_NAME;
        } else {
            $this->database = MONGO_DB_NAME.$_database;
        }
        if (empty(MONGO_DB_USER)) {
            $this->mongoAccess = new MongoDB\Driver\Manager('mongodb://' . MONGO_HOSTNAME);
        } else {
            $this->mongoAccess = new MongoDB\Driver\Manager('mongodb://' . MONGO_DB_USER . ':' . MONGO_DB_PASSWORD . '@' . MONGO_HOSTNAME);
        }
        /**
          if (empty(MONGO_DB_USER)) {
          $this->mongoAccess = new MongoDB\Driver\Manager('mongodb://' . MONGO_HOSTNAME);
          } else {
          $this->mongoAccess = new MongoDB\Driver\Manager('mongodb://' . MONGO_DB_USER . ':' . MONGO_DB_PASSWORD . '@' . MONGO_HOSTNAME);
          }
         * 
         */
        $this->bulk = new MongoDB\Driver\BulkWrite();

        return $this->mongoAccess;
    }

    public function insert($collection, $doc, $alert = NULL) {
        try {
            if (is_array($doc)) {
                $this->bulk->insert($doc);
                $this->mongoAccess->executeBulkWrite($this->database . '.' . $collection, $this->bulk);    # 'schooldb' is database and 'student' is collection.   
                if ($alert == 2) {
                    return true;
                } elseif ($alert == 1) {
                    tool::alert('Salvo com Sucesso!');
                }
            }
        } catch (Exception $exc) {
            if ($alert == 2) {
                return;
            } elseif ($alert == 1) {
                tool::alert('Algo errado não está certo!');
            }
        }
    }

    public function query($collection, $filter = [], $option = []) {

        $query = new MongoDB\Driver\Query($filter, $option);
        $rows = $this->mongoAccess->executeQuery($this->database . '.' . $collection, $query);
        $array = $rows->toArray();

        return $array;
    }

    /**
     * $array = $mongo->update('usuarios',['nome' =>'Sandra'], ['cpf'=> 2222222222222], NULL, 1);
     * @param type $collection 
     * @param type $criterion
     * @param type $set
     * @param boolean $option  padrao: ['multi' => false, 'upsert' => true] upsert criar se não existir
     * @param type $alert
     */
    public function update($collection, $criterion, $set, $option = NULL, $alert = NULL) {
        if (empty($option)) {
            $option = ['multi' => false, 'upsert' => true];
        }
        try {
            $array = $this->bulk->update($criterion, ['$set' => $set], $option);
            $this->mongoAccess->executeBulkWrite($this->database . '.' . $collection, $this->bulk);    # 'schooldb' is database and 'student' is collection.   
            if ($alert == 2) {
                return true;
            } elseif ($alert == 1) {
                tool::alert('Atualizado com Sucesso!');
            }
        } catch (Exception $exc) {
            if ($alert == 2) {
                return;
            } elseif ($alert == 1) {
                tool::alert('Algo errado não está certo!');
            }
        }
    }

    public function delete($collection, $criterion, $option = ['limit' => 0], $alert = NULL) {
        try {
            $this->bulk->delete($criterion, $option);
            $result = $this->mongoAccess->executeBulkWrite($this->database . '.' . $collection, $this->bulk);
            if ($alert == 2) {
                return true;
            } elseif ($alert == 1) {
                tool::alert('Apagado com Sucesso!');
            }
        } catch (Exception $exc) {
            if ($alert == 2) {
                return;
            } elseif ($alert == 1) {
                tool::alert('Algo errado não está certo!');
            }
        }
    }

    public function deleteId($collection, $id, $alert = NULL) {
        $id = ['_id' => new MongoDB\BSON\ObjectId($id)];
        $this->delete($collection, $id, ['limit' => 0], $alert);
    }

}
