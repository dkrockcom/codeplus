<?php

class BusinessBase
{
    public $Id;

    public function __construct()
    {
        //TODO: Bind DB Object by default.
    }

    /**
     * Save method - Perform action record create and update. perform update when id is set.
     * @param Nullable int $id - Table key field Id
     *
     * @return string json string
     */
    public function save($id = null)
    {
        $db = new Database();
        $db->startTransaction();
        try {
            $properties = get_object_vars($this);
            array_splice($properties, array_search(ID, array_keys($properties)), 1);
            if (is_null($id)) {
                $li = $id = $db->insert($this->tableName(), $properties);
            } else {
                $db->where($this->keyField(), $id);
                $db->update($this->tableName(), $properties);
                if ($db->count == 0) {
                    throw new Exception("Invalid record");
                }
            }
            $db->commit();
            if ($db->_stmtErrno > 0) {
                throw new Exception($db->_stmtError);
            }
            $this->load(isset($id) ? $id : $li);
        } catch (Exception $ex) {
            $db->rollback();
            $this->error($ex->getMessage());
            exit();
        }
    }

    /**
     * load method - Load business class object with assiciated reqord id
     * @param int $string - * @param Nullable int $id - Table key field Id
     */
    public function load($id)
    {
        try {
            $db = new Database();
            $tableName = $this->tableName();
            $properties = get_object_vars($this);
            $db->where($this->keyField(), $id);
            $data = $db->getOne($tableName);
            if ($data) {
                foreach ($properties as $key => $value) {
                    $this->{$key} = $key == ID ? $data[$this->keyField()] : $data[$key];
                }
            }
        } catch (Exception $ex) {
            $this->error($ex->getMessage());
            exit();
        }
    }

    /**
     * delete method - Perform action delete record.
     * @param int $string - * @param Nullable int $id - Table key field Id
     */
    public function delete($id)
    {
        $db = new Database();
        $db->startTransaction();
        try {
            $db->where($this->keyField(), $id);
            $db->delete($this->tableName());
            $db->commit();
        } catch (Exception $ex) {
            $db->rollback();
            $this->error($ex->getMessage());
            exit();
        }
    }

    /**
     * getList method - Perform action get the record list with associate controller class
     * @param Nullable_int $startIndex -
     * @param Nullable_int $limit - Json association
     * @param Array $filters - filters
     *
     * @return Array listData
     */
    public function getList($startIndex = null, $limit = null, $filters = array())
    {
        $records = array();
        try {
            $db = new Database();
            $db->orderBy($this->keyField(), "Desc");
            if (sizeof($filters) == 0) {
                $records = $db->withTotalCount()->get($this->tableName(), array($startIndex, $limit));
            } else {
                $flt = new Filter();
                $flt->applyFilters($filters, $db);
                $records = $db->withTotalCount()->get($this->tableName(), array($startIndex, $limit));
            }
        } catch (Exception $ex) {
            $this->error($ex->getMessage());
            exit();
        }
        return $records;
    }

    /**
     * error method - Generate error output with message
     * @param string $message - Error meesgae
     */
    public function error($message)
    {
        $response = array(SUCCESS => false, ERROR => $message);
        Common::serializeObject($response);
        exit();
    }

    /**
     * tableName method - Get the table name
     * @return string Associate controller class name
     */
    public function tableName()
    {
        return get_called_class();
    }

    /**
     * keyField method - Get Key field
     * @return int table key field
     */
    public function keyField()
    {
        return $this->tableName() . ID;
    }

    /**
     * getProperties method - Get associate controller class properties
     * @return Array Associate controller class properties
     */
    public function getProperties()
    {
        return get_object_vars($this);
    }
}
