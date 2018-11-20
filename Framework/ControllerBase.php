<?php

class ControllerBase
{
    private $IsAuthEnabled = true;
    private $Request;
    private $RequestType;
    private $Header;
    public $Action;
    private $Id = null;
    private $StartIndex = null;
    private $Limit = null;
    public $Filters = array();
    public $Context = null;
    public $db = null;
    public $Sort = null;
    public $Dir = null;
    public $Combos = array();

    /**
     * __construct constructor parse the field value and return parsed value
     * @param object $context - Child class onject
     * @param boolean $isAuthEnabled - check controller have authrization check or not.
     */
    public function __construct($context = null, $isAuthEnabled = true)
    {
        $this->db = new Database();
        $this->Context = $context;
        $this->IsAuthEnabled = $isAuthEnabled;
        $this->init();
    }

    //Controller base initialization.
    private function init()
    {
        $this->RequestType = $_SERVER[REQUEST_METHOD];
        //Authorization Check.
        if ($this->IsAuthEnabled && !Identity::authorization()) {
            exit();
        }

        $this->Request = ($this->RequestType == POST ? $_POST : ($this->RequestType == GET ? $_GET : null));
        $this->Action = $this->params("action", false, null);
        if ($this->Request != null && $this->Context != null) {
            $this->Id = $this->params("id", false, null);
            $this->StartIndex = $this->params("startindex", false, 0);
            $this->Limit = $this->params("limit", false, 25);
            $this->Filters = $this->params("filters", false, array());
            $this->Sort = $this->params("sort", false, null);
            $this->Dir = $this->params("dir", false, null);
            $this->Combos = $this->params("combos", false, array());
        }
        $this->actionHandler();
    }

    /**
     * setProperties method set default value or user define properites for all table common columns
     * @param boolean $isUpdate - check for update values or default update.
     */
    private function setProperties($isUpdate)
    {
        $properties = $this->Context->getProperties();
        foreach ($properties as $key => $value) {
            if ($key == ID) {
                continue;
            }
            if (isset($_POST[$key])) {
                $this->Context->$key = $_POST[$key];
            }
        }
        if (array_key_exists(CREATED_ON, $properties) && !$isUpdate) {
            $this->Context->CreatedOn = date(AppConfig::DATETIME_FORMAT);
        }
        if (array_key_exists(CREATED_BY, $properties) && !$isUpdate) {
            $this->Context->CreatedBy = array_key_exists(HTTP_AUTHORIZATION, $_SERVER) ? Identity::userIdentity()->UserId : 0;
        }
        if (array_key_exists(MODIFIED_ON, $properties) && $isUpdate) {
            $this->Context->ModifiedOn = date(AppConfig::DATETIME_FORMAT);
        }
        if (array_key_exists(MODIFIED_BY, $properties) && $isUpdate) {
            $this->Context->ModifiedBy = array_key_exists(HTTP_AUTHORIZATION, $_SERVER) ? Identity::userIdentity()->UserId : 0;
        }
    }

    /**
     * actionHandler method - Request Action handler: Handle request Save/Load/Delete/List and other actions and default.
     */
    public function actionHandler()
    {
        $reponse = array();
        try {
            switch (strtoupper($this->Action)) {
                case SAVE:
                    if (!is_null($this->Id) && ($this->Id == "" || $this->Id == 0)) {
                        $reponse = array(SUCCESS => false, ERROR => ID_NOT_BLANK_ZERO);
                        return;
                    }
                    if (isset($this->Id)) {
                        $this->Context->load($this->Id);
                    }
                    $this->setProperties(!is_null($this->Id));
                    $this->Context->save($this->Id);
                    $reponse = array(SUCCESS => true, DATA => array($this->Context), MESSAGE =>
                        (isset($this->Id) ? RECORD_SUCCESSFULLY_UPDATED : RECORD_SUCCESSFULLY_CREATED));
                    Common::serializeObject($reponse);
                    break;

                case LOAD:
                    if (isset($this->Id)) {
                        $this->Context->Load($this->Id);
                        $hasData = !is_null($this->Context->Id);
                        $reponse[SUCCESS] = $hasData;
                        if ($hasData) {
                            $comboData = $this->getCombos($this->Combos);
                            $reponse[DATA] = array($this->Context);
                            $reponse["Combos"] = $comboData;
                        } else {
                            $reponse[MESSAGE] = RECORDS_NOT_EXISTS;
                        }
                    } else {
                        $reponse = array(SUCCESS => false, ERROR => ID_NOT_BLANK_ZERO);
                    }
                    Common::serializeObject($reponse);
                    break;

                case DELETE:
                    if (isset($this->Id)) {
                        $this->Context->delete($this->Id);
                        $reponse = array(SUCCESS => true, MESSAGE => RECORDS_BEEN_SUCCESSFULLY_DELETED);
                    } else {
                        $reponse = array(SUCCESS => false, ERROR => ID_NOT_BLANK_ZERO);
                    }
                    Common::serializeObject($reponse);
                    break;

                case GETLIST:
                    $records = array();
                    $recordCount = 0;
                    $db = new Database();
                    $tableView = 'vw' . $this->Context->tableName() . 'List';
                    $dir = isset($this->Dir) ? $this->Dir : "DESC";
                    $sort = isset($this->Sort) ? $this->Sort : $this->Context->keyField();
                    $comboData = $this->getCombos($this->Combos);
                    if (sizeof($this->Filters) == 0) {
                        $recordCount = $db->getValue($tableView, "count(*)");
                        $db->orderBy($sort, $dir);
                        $records = $db->withTotalCount()->get($tableView, array($this->StartIndex, $this->Limit));
                    } else {
                        $flt = new Filter();
                        $flt->applyFilters($this->Filters, $db);
                        $recordCount = $db->getValue($tableView, "count(*)");
                        $flt = new Filter();
                        $db = new Database();
                        $flt->applyFilters($this->Filters, $db);
                        $db->orderBy($sort, $dir);
                        $records = $db->withTotalCount()->get($tableView, array($this->StartIndex, $this->Limit));
                    }
                    Common::serializeObject(array(SUCCESS => true, DATA => $records, RECORD_COUNT => $recordCount, "Combos" => $comboData));
                    break;

                case OTHERACTION:
                default:
                    $this->execute();
                    break;
            }
        } catch (Exception $ex) {
            Common::serializeObject(array(SUCCESS => false, ERROR => $ex->getMessage()));
        }
        exit();
    }

    /**
     * params method - get params with default values if define, which is pass from user request.
     * @param string $field - Field name
     * @param boolean $required - true/false if field have required validation or not
     * @param string $defaultValue - Default value for field
     *
     * @return FieldValue
     */
    public function params($field, $required = false, $defaultValue = null)
    {
        $requestParam = $this->requestParam();
        $fieldValue = array_key_exists($field, $requestParam) ? $requestParam[$field] : (isset($defaultValue) ? $defaultValue : null);
        if ($field == "filters" || $field == "combos") {
            if (sizeof($fieldValue) == 0) {
                return $fieldValue;
            }
            $fieldValue = Common::toValidJson($fieldValue);
            return $fieldValue;
        }
        $fieldValue = strtolower($fieldValue) == "null" ? null : $fieldValue;
        if (empty($defaultValue) && empty($fieldValue) && $required) {
            Common::serializeObject(array(SUCCESS => false, ERROR => $field . SHOULD_NOT_BE_BLANK));
            exit();
        }
        return $fieldValue;
    }

    /**
     * getCombos method - Get Combos data in array, which are passes from client side.
     * @param array $combos - Combos which are passed from client side.
     *
     * @return Comobos Data array
     */
    private function getCombos($combos)
    {
        $comboData = array();
        $lookupTypes = new ReflectionClass('LookupType');
        foreach ($combos as $key => $value) {
            if ($lookupTypes->getConstant($value)) {
                $comboData[$value] = $this->getComboData($lookupTypes->getConstant($value));
            }
        }
        return $comboData;
    }

    /**
     * getComboData method - Get Combo Data based on lookup id.
     * @param int $lookupId - lookup id for find the combo items records
     *
     * @return ComboRecords
     */
    private function getComboData($lookupId)
    {
        $db = new Database();
        $db->join("Lookup l", "l.LookupTypeId=lt.LookupTypeId", "LEFT");
        $db->joinWhere("Lookup l", "l.LookupTypeId", $lookupId);
        $db->orderBy("l.DisplayValue", "ASC");
        $data = $db->get("LookupType lt", null, "l.LookupId, l.DisplayValue");
        return $data;
    }

    /**
     * requestParam method return http method post/get
     * @return http method
     */
    private function requestParam()
    {
        return $this->RequestType == POST ? $_POST : $_GET;
    }
}
