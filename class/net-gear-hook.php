<?php
abstract class NetGearHook{
    public abstract function execute();
}
abstract class NetGearFilter extends NetGearHook{
    public function get(){
        ob_start();
        $output = $this->execute();
        $ob = ob_get_clean();
        //
        return empty($ob) ? $output : $ob;
    }
}
abstract class NetGearAction extends NetGearHook{ //  extends NetGearFilter
    public function get(){
        $this->execute();
    }
}