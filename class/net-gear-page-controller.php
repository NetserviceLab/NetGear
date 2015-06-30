<?php
abstract class NetGearPageController{
    private $slug;
    private $bootstraped;
    private $sub_controllers;
    private $twig_functions;
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

    protected function display($view, $data=null)
    {
        if (!class_exists('WP_Twig_Templating')) {
            throw new Exception("WP_Twig_Templating viene richiesta");
        }
        if (!$data) $data = array();

        $other = ['controller'=>$this];

        $_data = array_merge($data,$other);
        WP_Twig_Templating::istance()->displayCustomPage($this->pluginBaseDir() . $view, $_data);
    }

    public function addTwigFunction($name, $function){
        $this->twig_functions[$name] = $function;
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

    private function pluginBaseDir(){
        $ar = explode('/', $this->getPlugin()->plugin_dir_path() );
        // tolgo lo slash finali
        array_pop($ar);
        return (end($ar).'/');
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
            WP_Twig_Templating::istance()->getTwig()->addFunction(new Twig_SimpleFunction($name,$function));
        }
    }

    /************************   PUBLIC METHODS   ***********************/

    /**
     * @param $file __FILE__
     */
    public final function __construct(){
        $this->bootstraped = false;
        $this->sub_controllers = array();
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
     * Ritorna il percorso dell'icona di menu
     * @return null|string
     */
    public function getIconUrl(){
        return null;
    }

    /**
     * Ritorna la posizione della voce di menu nell'elenco
     * @return null|integer
     */
    public function getMenuPosition(){
        return null;
    }

    /**
     * Aggiunge un sottocontroller con relativo link al menu del controller
     * @param NetGearPageController $page
     */
    public final function addSubPageController(NetGearPageController $page){
        $slug = $this->getSlug() . '_'. get_class($page).'_'.count($this->sub_controllers);
        array_push($this->sub_controllers, $page->setSlug($slug));
        $page->setPlugin($this->getPlugin());
        //todo set parent controller
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


    public final function render_page(){
        $this->addTwigFunctions();

        if($this->getAction() == 'default'){
            $this->defaultAction();
            return;
        }

        $method = $this->getAction().'Action';
        if(method_exists($this,$method)){
            call_user_func(array($this,$method));
        }else{
            throw new Execption("$method not exists!");
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
     * @param NetGear $plugin
     * @return $this
     */
    public final function setPlugin(NetGear $plugin){
        $this->plugin = $plugin;
        return $this;
    }


    public function getActionUrl($action){
        if(!method_exists($this,$action.'Action')){
            return "javascript:alert('Action non presente: $action');";
        }
        return "/wp-admin/admin.php?page=".$this->getSlug().'&action='.$action;
    }

    public function getAjaxActionUrl($action){
        return strtolower($this->getSlug().'_'.$action.'Ajax');
    }
}