<?php

class Filter
{
    public function __construct()
    {

    }

    /**
     * applyFilters method returns JWT Token
     * @param array $filters filters array which are passed in user request:
     * @param object $db databse instance
     * @param string $time - Jwt security token
     */
    public function applyFilters($filters, $db)
    {
        foreach ($filters as $key => $fieldValue) {
            $fieldName = $fieldValue[FIELD_NAME];
            $type = strtoupper($fieldValue[FILTER_TYPE]);
            $value = $fieldValue[VALUE];
            $condition = strtoupper($fieldValue[CONDITION]);

            $fieldName = $type == DATE ? 'date(' . $fieldName . ')' : $fieldName;
            $value = $this->parseFieldValue($type, $value, $condition);

            switch ($condition) {
                case EQUAL:
                    $db->where($fieldName, $value, "=");
                    break;
                case NOT_EQUAL:
                    $db->where($fieldName, $value, "<>");
                    break;
                case GREATER_THEN:
                    $db->where($fieldName, $value, ">");
                    break;
                case GREATER_THEN_OR_EQUAL:
                    $db->where($fieldName, $value, ">=");
                    break;
                case LESS_THEN:
                    $db->where($fieldName, $value, "<");
                    break;
                case LESS_THEN_OR_EQUAL:
                    $db->where($fieldName, $value, "<=");
                    break;
                case LIKE:
                    $db->where($fieldName, $value . "%", "like");
                    break;
                case IN:
                    $db->where($fieldName, $value, "IN");
                    break;
                case NOTIN:
                    $db->where($fieldName, $value, "NOT IN");
                    break;

                default:
                    # code...
                    break;
            }
        }
    }

    /**
     * parseFieldValue method parse the field value and return parsed value
     * @param string $type - Database datatype.
     * @param string $value - Field value
     * @param string $condition - Database expression
     *
     * @return string
     */
    private function parseFieldValue($type, $value, $condition)
    {
        if ($condition == IN || $condition == NOTIN) {
            $value = explode(',', $value);
            return $value;
        }
        switch (strtoupper($type)) {
            case INT:
                $value = (int) $value;
                break;
            case STRING:
                $value = (string) $value;
                break;
            case DATE:
                $value = strtotime($value);
                $value = date('Y-m-d', $value);
                break;
            case DECIMAL:
            case DOUBLE:
                $value = floatval($value);

                break;
            default:
                # code...
                break;
        }
        return $value;
    }
}
