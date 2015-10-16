<?php

class NetGearAutoloader
{
    private $models;
    private $repositories;

    private $modelDir;
    private $repoDir;

    /**
     * NetGearAutoloader constructor.
     * @param $plugin_base_dir
     */
    public function __construct($plugin_base_dir)
    {
        $this->modelDir = $plugin_base_dir.'/model/';
        $this->repoDir = $plugin_base_dir.'/repository/';

        if( file_exists($this->modelDir) && is_dir($this->modelDir)){
            $this->models = scandir($this->modelDir);
            $this->loadModels();
        }
        if( file_exists($this->repoDir) && is_dir($this->repoDir) ){
            $this->repositories = scandir($this->repoDir);
            $this->loadRepositories();
        }
    }

    private function loadModels()
    {
        $this->includeFile($this->modelDir,$this->models);
    }

    private function loadRepositories()
    {
        $this->includeFile($this->repoDir,$this->repositories);
    }

    private function includeFile($baseDir,$files)
    {
        // Folder is empty
        if( count($files) <= 2 ){
            return;
        }

        foreach ($files as $file ) {
            if($file == '.' || $file == '..'){
                continue;
            }
            $ar = explode('.',$file);
            $est = end($ar);
            if($est == 'php' && file_exists($baseDir.'/'.$file)){
                require_once $baseDir.'/'.$file;
            }
        }

    }

}