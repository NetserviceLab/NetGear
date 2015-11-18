<?php
abstract class NetGearPageController{
    private $slug;
    private $bootstraped;
    private $sub_controllers;
    private $twig_functions;
    private $file;
    /** @var  wpdb $wpdb */
    private $wpdb;
    /** @var  NetGear $plugin */
    private $plugin;

    /************************   ABSTRACT METHODS   ***********************/
    public abstract function getMenuTitle();
    public abstract function getPageTitle();

    public abstract function init();
    protected abstract function defaultAction();

    /************************   PROTECTED METHODS   ***********************/

    /**
     * @param $view
     * @param mixed|null $data
     * @throws Exception
     */
    protected function display($view, $data=null)
    {
        if (!class_exists('WP_Twig_Templating')) {
            throw new Exception("WP_Twig_Templating viene richiesta");
        }
        if (!$data) $data = array();

        $other = ['controller'=>$this,'currentPluginTwigBasePath' => $this->pluginBaseDir().'views/'];

        $_data = array_merge($data,$other);
        WP_Twig_Templating::istance()->displayCustomPage($this->pluginBaseDir().'views/' . $view, $_data);
    }

    public function addTwigFunction($name, $function){
        if( !array_key_exists($name,$this->twig_functions)){
            $this->twig_functions[$name] = $function;
        }
    }

    protected function displayAjaxTemplate($view, $data=null){
        $this->display($view,$data);
        exit();
    }

    protected function jsonResponse($data){
        header('Content-Type: application/json');
        echo json_encode($data);
        exit();
    }

    protected function getRequestPayload(){
        return file_get_contents('php://input');
    }

    protected function payloadParam($key,$default = null){
        $input = $this->getRequestPayload();
        $var = json_decode($input,true);
        if(empty($var)){
            return $default;
        }
        return array_key_exists($key,$var) ? $var[$key]  : $default;
    }
    /************************   PRIVATE METHODS   ***********************/
    /**
     * Ritorna il valore della action considerata
     * @return string
     */
    private function getAction(){
        return array_key_exists('action',$_GET) ? $_GET['action'] : 'default';
    }

    protected function pluginBaseDir(){
        $ar = explode('/',dirname($this->file));
        if( in_array("plugins",$ar)){
            while( $ar[count($ar)-2] != "plugins" && count($ar) != 0 ){
                unset($ar[count($ar)-1]);
            }
        }
        return (end($ar).'/');
//        $ar = explode('/',plugin_dir_path($this->file));
//        $ar = array_filter($ar);
//        return (end($ar).'/');
    }

    private function check_methods(){
        $class_methods = get_class_methods($this);
        foreach ($class_methods as $method_name) {
            //echo "$method_name\n";
            if(substr($method_name, -4) == 'Ajax'){
                add_action( 'wp_ajax_'.strtolower($this->getSlug().'_'.$method_name), array($this,$method_name) );
                //echo 'wp_ajax_'.$this->getSlug().'_:'.$method_name.' *** ';
            }
        }
    }

    private function addDefaultTwigFunctions(){
        $this->addTwigFunction('actionPath',function($action){
            return $this->getActionUrl($action);
        });
        $this->addTwigFunction('ajaxPath',function($action, $js = true){
            return $js ? "ajaxurl+'?action=".$this->getAjaxActionUrl($action)."'" : $this->getAjaxActionUrl($action);
        });
    }
    private function addTwigFunctions(){
        foreach($this->twig_functions as $name => $function){
            $twig = WP_Twig_Templating::istance()->getTwig();
            try{
                $twig->addFunction(new Twig_SimpleFunction($name,$function));
            }catch (LogicException $e){
                echo "C'è qualche problema di render. Controlla che il file .html.twig non sia renderizzato più volte";
            }
        }
    }

    /************************   PUBLIC METHODS   ***********************/

    /**
     * @param $file string __FILE__
     */
    public final function __construct($file){
        $this->bootstraped = false;
        $this->sub_controllers = array();
        $this->file = $file;
        //
        global $wpdb;
        $this->wpdb = $wpdb;
        //
        $this->twig_functions = array();
        //
    }

    /**
     * Ritorna l'istanza di wpdb
     * @return wpdb
     */
    public function wpdb(){
        return $this->wpdb;
    }

    /**
     * Aggiunge un sottocontroller con relativo link al menu del controller
     * @param NetGearPageController $page
     */
    public final function addSubPageController(NetGearPageController $page){
        $slug = $this->getSlug() . '_'. get_class($page).'_'.count($this->sub_controllers);
        array_push($this->sub_controllers, $page->setSlug($slug));
        $page->setPlugin($this->getPlugin());
        $page->bootstrap();
    }

    /**
     * Ritorna i sotto controller
     * @return NetGearPageController[]
     */
    public final function getSubPageControllers(){
        return $this->sub_controllers;
    }

    /**
     * Ritorna il percorso dell'icona di menu
     * @return null|string
     */
    public function getIconUrl(){
        return null;
    }
    /**
     * Ritorna la posizione della voce di menu nell'elenco
     * @return null|float
     */
    public function getMenuPosition(){
        return null;
    }

    /**
     * Ritorna il livello di privilegio per accedere alla pagina
     * @return string 'administrator'
     */
    public function getCapability(){
        return 'administrator';
    }

    /**
     * Imposta lo slug utile per i menu
     * @param $s string
     * @return $this
     */
    public final function setSlug($s){
        $this->slug = $s;
        return $this;
    }

    /**
     * Ritorna lo slug
     * @return mixed
     */
    public final function getSlug(){
        return $this->slug;
    }

    /**
     * Avvia il controller
     * @throws Execption
     */
    public final function bootstrap(){
        if($this->bootstraped)return;
        //
        $this->addDefaultTwigFunctions();
        $this->init();
        //
        $this->check_methods();
        //

        //
        $this->bootstraped = true;
    }

    private function isMainPageController()
    {
        foreach ($this->getPlugin()->getPageController() as $subController) {
            $subClass = get_class($subController);
            if( $subClass == get_class($this) ){
                return true;
            }
        }
        foreach ($this->getPlugin()->getNetworkPageController() as $subController) {
            $subClass = get_class($subController);
            if( $subClass == get_class($this) ){
                return true;
            }
        }
        return false;
    }

    public final function render_page(){
        if( count($this->getSubPageControllers()) > 0 ){
            return;
        }
        $this->addTwigFunctions();

        if($this->getAction() == 'default'){
            $this->defaultAction();
            return;
        }

        $method = $this->getAction().'Action';
        if(method_exists($this,$method)){
            call_user_func(array($this,$method));
        }else{
            $isSubMethod = false;
            foreach ($this->getSubPageControllers() as $subController) {
                if(method_exists($subController,$method)) {
                    call_user_func(array($subController, $method));
                    $isSubMethod = true;
                }
            }
            if( !$isSubMethod ){
                throw new Exception("$method not exists!");
            }
        }
    }

    /**
     * Ritorna l'istanza del plugin padre
     * @return NetGear
     */
    public final function getPlugin(){
        return $this->plugin;
    }

    /**
     * Imposta il plugin padre
     * @param $plugin
     * @return $this
     */
    public final function setPlugin($plugin){
        $this->plugin = $plugin;
        return $this;
    }


    public function getActionUrl($action){
        if(!method_exists($this,$action.'Action')){
            $found = false;
            foreach ($this->getSubPageControllers() as $subController) {
                if(method_exists($subController,$action.'Action')) {
                    $found = true;
                }
            }
            if( !$found){
                return "javascript:alert('Action non presente: $action');";
            }
//            return "javascript:alert('Action non presente: $action');";
        }
        if(is_network_admin()){
            return "/wp-admin/network/admin.php?page=".$this->getSlug().'&action='.$action;
        }else{
            return "/wp-admin/admin.php?page=".$this->getSlug().'&action='.$action;
        }
    }

    public function getAjaxActionUrl($action){
        return strtolower($this->getSlug().'_'.$action.'Ajax');
    }
}