<?php

abstract class NetGear{
    private $runned;
    private $file;
    private $version;
    private $styles;
    private $script;
    private $actions;
    private $filters;
    /** @var NetGearPageController[] $page_controllers */
    private $page_controllers;
    /** @var  wpdb $wpdb */
    private $wpdb;

    /************************   STATIC METHODS   ***********************/

    /**
     * Mostra gli errori
     */
    public static final function showError(){
        error_reporting(E_ERROR | E_WARNING | E_PARSE); // E_ALL
        ini_set('display_errors', 1);
    }

    /**
     * Mostra un alert javascript
     * @param $mex
     */
    public final static function alert($mex){
        echo '<script>alert("'.$mex.'");</script>';
    }

    /**
     * Carica la classe principale del plugin e la istanzia
     * @param $file percorso del file plugin.php
     */
    public final static function bootstrapPlugin($file){
        $path = dirname($file);
        $par = explode('/',$path);
        $plugin_dir = end($par);
        $plugin_class_name = implode("",explode(" ",ucwords(str_replace("-"," ",$plugin_dir))));

        $filename = $path.'/'.$plugin_class_name.'.php';
        if(!file_exists($filename)){
            self::alert("Il plugin '.$plugin_class_name.' non esiste");
            return;
        }

        require_once $filename;

        /** @var NetGear $plugin */
        $plugin = new $plugin_class_name($filename);
        if(!($plugin instanceof NetGear)){
            throw new Exception("Il plugin $plugin_class_name deve estendere la classe NetGear");
        }
        $plugin->init();
        $plugin->run();
    }

    /**
     * Scatena l'evento per caricare i plugin che stavano in ascolto
     */
    public final static function bootstrap(){
        NetGear::showError();
        do_action('netgear_loaded');
    }

    /************************   ABSTRACT METHODS   ***********************/

    protected abstract function init();



    /************************   PROTECTED METHODS   ***********************/

    /**
     * Inizializza il plugin del file
     * @param $file string __FILE__
     */
    public final function __construct($file){
        $this->runned = false;
        $this->file = $file;
        $this->version = 1;
        $this->styles = array();
        $this->script = array();
        $this->actions = array();
        $this->filters = array();
        $this->page_controllers = array();
        //
        $this->load_deps_in_folder('controller');
        //
        global $wpdb;
        $this->wpdb = $wpdb;
    }

    /**
     * Aziona il plugin
     */
    public final function run(){
        if($this->runned)return;
        //
        $this->generate_hooks();
        //$this->init_controllers();
        $this->generate_style();
        $this->generate_script();
        $this->runned=true;
        //
        add_action('admin_menu',array($this,'generate_menu_page'));
    }


    /**
     * @param $v integer Numero di versione da sovrascrivere per file asset
     * @return $this
     */
    protected final function setVersion($v){
        $this->version = $v;
        return $this;
    }


    /**
     * Aggiunge un controller con relativa pagina
     * @param NetGearPageController $page
     * @return $this
     */
    protected final function addPageController(NetGearPageController $page){
        $slug = get_class($page).'_page_'.count($this->page_controllers);
        if(array_key_exists($slug,$this->page_controllers)){
            return $this;
        }
        $this->page_controllers[$slug] = $page->setSlug($slug);
        $page->setPlugin($this);
        $page->bootstrap();
        return $this;
    }


    /************************   PRIVATE METHODS   ***********************/

    /**
     * Genera i listener alle action e ai filter
     */
    private function generate_hooks() {
        foreach($this->actions as $h){
            add_action($h['hook'],array($this,'apply_action'),$h['id']);
        }
        foreach($this->filters as $h){
            add_filter($h['hook'],array($this,'apply_filter'),$h['id']);
        }
    }

    /**
     * Genera i listener alle action e ai filter
     */
    private function init_controllers() {
        foreach($this->page_controllers as $c){
            $c->bootstrap();
        }

    }

    /**
     * Effettua le chiamate a Wordpress per aggiungere gli stili
     */
    private function generate_style(){
        foreach($this->styles as $id => $path){
            wp_enqueue_style(
                $id,
                $path,
                array(),
                $this->version,
                FALSE
            );
        }
    }

    /**
     * Effettua le chiamate a Wordpress per aggiungere gli script
     */
    private function generate_script(){
        foreach($this->script as $id => $path){
            wp_enqueue_script(
                $id,
                $path,
                array(),
                $this->version,
                FALSE
            );
        }
    }

    /**
     * Genera una stringa contenente il nome della classe figlia che estende NetGear
     * @return string
     */
    private function slug(){
        $class = get_class($this);
        return strtolower($class);
    }


    private function load_deps_in_folder($folder){
        $path = $this->plugin_dir_path().$folder;
        $files = scandir($path);
        foreach($files as $f){
            if($f == '.' || $f == '..'){
                continue;
            }
            $ar = explode('.',$f);
            $est = end($ar);
            if($est == 'php' && file_exists($path.'/'.$f)){
                include_once $path.'/'.$f;
            }
        }
    }

    /************************   PUBLIC METHODS   ***********************/


    /**
     * Restituisce la versione degli assets
     * @return integer
     */
    public final function getVersion(){
        return $this->version;
    }



    /**
     * Aggiunge uno style alla head
     * @param $path
     * @param bool $plugin_dir
     * @return $this
     */
    public function enqueueStyle($path, $plugin_dir = true) {
        $id = $this->slug().'-style-'.count($this->styles);

        $this->styles[$id] = $plugin_dir ? ($this->plugin_dir_url() . $path) : $path;

        return $this;
    }

    /**
     * Aggiunge uno script in testata
     * @param $path
     * @param bool $plugin_dir
     * @return $this
     */
    public function enqueueScript($path, $plugin_dir = true){
        $id = $this->slug().'-script-'.count($this->script);

        $this->script[$id] = $plugin_dir ? ($this->plugin_dir_url() . $path) : $path;

        return $this;
    }

    public function getNetGearPublicPath($relativePath){
        return plugin_dir_url(dirname(__FILE__)).$relativePath;
    }

    /**
     * Ritorna la path web
     * @return string
     */
    public final function plugin_dir_url(){
        return plugin_dir_url($this->file);
    }
    /**
     * Ritorna la path del server
     * @return string
     */
    public final function plugin_dir_path(){
        return dirname($this->file).'/';
    }



    /**
     * Aggiunge un oggetto action in ascolto dell'azione $hook
     * @param $hook
     * @param NetGearAction $action
     * @return $this
     */
    public final function addAction($hook, NetGearAction $action) {
        array_push($this->actions, array(
            'hook'      => $hook,
            'component' => $action,
            'id'=>count($this->actions)
        ));
        return $this;
    }
    /**
     * Aggiunge un oggetto filter in ascolto dell'azione $hook
     * @param $hook
     * @param NetGearFilter $filter
     * @return $this
     */
    public final function addFilter($hook, NetGearFilter $filter) {
        array_push($this->filters, array(
            'hook'      => $hook,
            'component' => $filter,
            'id'=>count($this->filters)
        ));
        return $this;
    }



    /**
     * Ritorna l'istanza di wpdb
     * @return wpdb
     */
    public function wpdb(){
        return $this->wpdb;
    }


    /**
     * Applica la action, non dovrebbe essere mai chiamata dal programmatore ma solo da Wordpress
     * @param $id
     * @throws Exception
     */
    public final function apply_action($id){
        if(array_key_exists($id,$this->actions)){
            throw new Exception("L'azione cercata non esiste");
        }
        /** @var NetGearAction $action */
        $action = $this->actions[$id];
        $action->get();
    }

    /**
     * Applica il filtro, non dovrebbe essere mai chiamata dal programmatore ma solo da Wordpress
     * @param $id
     * @return mixed
     * @throws Exception
     */
    public final function apply_filter($id){
        if(array_key_exists($id,$this->filters)){
            throw new Exception("Il filtro cercato non esiste");
        }
        /** @var NetGearFilter $filters */
        $filters = $this->filters[$id];
        return $filters->get();
    }


    /**
     * Genera i menu per i page controller
     */
    public final function generate_menu_page(){
        /** @var $page_controllers $page */
        foreach($this->page_controllers as $page){
            add_menu_page($page->getPageTitle(),$page->getMenuTitle(),$page->getCapability(),$page->getSlug(),array($page,'render_page'),$page->getIconUrl(),$page->getMenuPosition());
            foreach($page->getSubPageControllers() as $subpage){
                add_submenu_page($page->getSlug(),$subpage->getPageTitle(),$subpage->getMenuTitle(),$subpage->getCapability(),$subpage->getSlug(),array($subpage,'render_page'),$subpage->getIconUrl(),$subpage->getMenuPosition());
            }
        }
    }
}