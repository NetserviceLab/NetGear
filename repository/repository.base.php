<?php


abstract class NetGearRepositoryBase
{
    /** @var  wpdb */
    protected $wpdb;
    private $class;
    private $modelClass;

    const CAST_TO_CUSTOM_CLASS = true;

    /**
     * RepositoryBase constructor.
     */
    public function __construct()
    {
        global $wpdb;
        $this->wpdb = $wpdb;
        $this->class = get_class($this);
        $this->modelClass = str_replace('Repository','',$this->class);
        if( !class_exists($this->modelClass)){
            $this->modelClass = class_exists("Model".$this->modelClass) ? "Model".$this->modelClass : null;
            if( $this->modelClass == null ){
                $this->modelClass = class_exists($this->modelClass."Model") ? $this->modelClass."Model" : null;
            }
        }
        if( $this->modelClass == null || !class_exists($this->modelClass)){
            throw new Exception("Non esiste una classe corrispondente al respository ".$this->class. ". Il model attuale e' ".$this->modelClass);
        }
    }

    public static function getInstance()
    {
        static $instance = null;
        if( $instance === null ){
            $class = get_called_class();
            $instance = new $class();
        }
        return $instance;
    }

    /**
     * @param $criteria
     * @param $cast
     * @return array|null|object
     */
    public function findOneBy($criteria,$cast = false)
    {
        $returnType = $cast ? OBJECT : ARRAY_A;
        $criteria = $this->parseCriteria($criteria);

        if( class_exists($this->modelClass)){
            $model = new $this->modelClass();
            $tableName = $this->wpdb->prefix.$model::TABLE_NAME;

            $res = $this->wpdb->get_row($this->wpdb->prepare("
                SELECT *
                FROM {$tableName}
                WHERE {$criteria["where"]}
            ",$criteria["values"] ),$returnType);

            return $cast ? $this->cast($this->modelClass,$res) : $res;
        }
        return null;
    }

    /**
     * @param $criteria
     * @param $cast
     * @return array|null|object
     */
    public function findBy($criteria,$cast = false)
    {
        $objectArray = array();
        $returnType = $cast ? OBJECT : ARRAY_A;
        $criteria = $this->parseCriteria($criteria);
        if( class_exists($this->modelClass) ){
            $model = new $this->modelClass();
            $tableName = $this->wpdb->prefix.$model::TABLE_NAME;

            $res = $this->wpdb->get_results($this->wpdb->prepare("
                SELECT *
                FROM {$tableName}
                WHERE {$criteria["where"]}
                {$criteria["order"]}
            ",$criteria["values"] ),$returnType);
            if( $cast ){
                foreach ($res as $row) {
                    $objectArray[] = $this->cast($this->modelClass,$row);
                }
            }
            return $cast ? $objectArray : $res;
        }
        return null;
    }

    private function parseCriteria($criteria)
    {
        $where = '1=1';
        $values = [];
        foreach ($criteria as $column => $value) {
            if( $column == "order" ){
                continue;
            }
            $type = is_numeric($value) ? '%d' : '%s';
            $where .= ' AND '.$column.'= '.$type;
            $values[] = $value;
        }
        return array(
            'where'     =>  $where,
            'values'    =>  $values,
            "order"     =>  isset($criteria["order"]) ? " ORDER BY ". $criteria["order"] : ""
        );
    }

    /**
     * Class casting
     *
     * @param string|object $destination
     * @param object $sourceObject
     * @return object
     */
    public function cast($destination, $sourceObject)
    {
        if( empty($sourceObject)){
            return null;
        }
        if (is_string($destination)) {
            $destination = new $destination();
        }
        $sourceReflection = new ReflectionObject($sourceObject);
        $destinationReflection = new ReflectionObject($destination);
        $sourceProperties = $sourceReflection->getProperties();
        foreach ($sourceProperties as $sourceProperty) {
            $sourceProperty->setAccessible(true);
            $name = $sourceProperty->getName();
            $value = $sourceProperty->getValue($sourceObject);
            if ($destinationReflection->hasProperty($name)) {
                $propDest = $destinationReflection->getProperty($name);
                $propDest->setAccessible(true);
                $propDest->setValue($destination,$value);
            } else {
                $destination->$name = $value;
            }
        }
        return $destination;
    }


}