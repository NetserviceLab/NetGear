<?php
abstract class NetGearHook{
    const DEFAULT_PRIORITY = 10;
    private $priority;
    private $accepted_args;
    private $args;
    public abstract function execute();

    public final function __construct($priority = 10, $accepted_args = 1){
        $this->priority = $priority;
        $this->accepted_args = $accepted_args;
        $this->args = array();
        //
        if(method_exists($this,'init')){
            $this->init();
        }
    }

    /**
     * @param $array
     * @return $this
     */
    public final function setArgs($array){
        $this->args = $array;
        return $this;
    }

    public final function getArg($index){
        return array_key_exists($index,$this->args) ? $this->args[$index] : null;
    }

    public final function getPriority(){
        return $this->priority;
    }

    public final function getAcceptedArgs(){
        return $this->accepted_args;
    }

}
abstract class NetGearFilter extends NetGearHook{
    public final function get(){
        $this->setArgs(func_get_args());
        return $this->execute();
        /*
        ob_start();
        $output = $this->execute();
        $ob = ob_get_clean();
        //
        return empty($ob) ? $output : $ob;
        */
    }
}
abstract class NetGearAction extends NetGearHook{ //  extends NetGearFilter
    public final function get(){
        $this->setArgs(func_get_args());
        $this->execute();
    }
}