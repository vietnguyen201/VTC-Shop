<?php
    class App{

        private $__controller;
        private $__action;
        private $__params;
        private $__routes;
        private $__db;

        public function __construct() {
            global $routes;
            $this->__routes = new Route();
            if(!empty ($routes['default_controller']))
            $this->__controller = $routes['default_controller'];
            $this->__action = "index";
            $this->__params = [];
            $this->handleUrl();
        }

        public function getUrl() {
            if(!empty ($_SERVER['PATH_INFO'])) {
                $url = $_SERVER['PATH_INFO'];
            }else {
                $url = '/';
            }
            return $url;
        }
        
        public function handleUrl() {
            $url = $this->getUrl();
            $url = $this->__routes->handleRoute($url);

            $this->handleAppServiceProvider($this->__db);
            $urlArr = array_filter(explode ('/', $url));
            $urlArr = array_values($urlArr);



            $urlCheck = '';
            if(!empty($urlArr)){

                foreach ($urlArr as $key => $item) {
                    $urlCheck.=$item.'/';
                    $fileCheck = rtrim($urlCheck,'/');
                    $fileArr = explode('/',$fileCheck);
                    $fileArr[count($fileArr) - 1] = ucfirst($fileArr[count($fileArr) - 1]);
                    $fileCheck = implode('/', $fileArr);
                    
                    if(!empty($urlArr[$key - 1])) {
                        unset ($urlArr[$key - 1]);
                    }
                    if(file_exists('site/controllers/'.$fileCheck.'Controller.php')) {
                        $urlCheck = $fileCheck;
                        break;
                    }
                }
                $urlArr = array_values($urlArr);
            }
            if(empty($urlCheck)) {
                $urlCheck = $this->__controller;
            }
            if(!empty ($urlArr[0])) {
                $this->__controller = ucfirst($urlArr[0]);
            }else {
                $this->__controller = ucfirst($this->__controller);
            }
            
            if(file_exists('site/controllers/'.$urlCheck.'Controller.php')) {
                require_once 'controllers/'.$urlCheck.'Controller.php'; 
                if(class_exists($this->__controller))
                $this->__controller =  new $this->__controller();
                unset($urlArr[0]);
            }else {
                $this->loadError();
            }
            
            
            if(!empty($urlArr[1])) {
                $this->__action = $urlArr[1];
                unset($urlArr[1]);
            }

            $this->__params = array_values($urlArr);

            if(method_exists($this->__controller, $this->__action)) {
                call_user_func_array([$this ->__controller, $this ->__action], $this->__params);
            }else {
                $this->loadError();
            }

        }
        public function loadError($name = '404') {
            require_once 'errors/'.$name.'.php';
        }
        public function handleAppServiceProvider($db) {
            global $conf;
            if(!empty($conf['app']['boot'])) {
                $SPArray = $conf['app']['boot'];
                foreach($SPArray as $serviceName) {
                    if(file_exists('site/core/'.$serviceName.'.php')) {
                        require_once 'site/core/'.$serviceName.'.php';
                        if(class_exists($serviceName)) {
                            $serviceObject = new $serviceName();
                            if(!empty($db)) {
                                $serviceObject->db = $db;
                            }
                            $serviceObject->boot();
                        }
                    }
                }
            }
        }
    }
?>