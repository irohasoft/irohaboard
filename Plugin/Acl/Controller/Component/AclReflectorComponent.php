<?php
class AclReflectorComponent extends Component
{
	private $controller = null;

	/****************************************************************************************/
    
    public function initialize(Controller $controller)
	{
	    $this->controller = $controller;
	}
	
	/****************************************************************************************/
	
	public function getPluginName($ctrlName = null)
	{
		$arr = String::tokenize($ctrlName, '/');
		if (count($arr) == 2) {
			return $arr[0];
		} else {
			return false;
		}
	}
	public function getPluginControllerName($ctrlName = null)
	{
		$arr = String::tokenize($ctrlName, '/');
		if (count($arr) == 2) {
			return $arr[1];
		} else {
			return false;
		}
	}
	public function get_controller_classname($controller_name)
	{
	    if(strrpos($controller_name, 'Controller') !== strlen($controller_name) - strlen('Controller'))
	    {
	        /*
	         * If $controller does not already end with 'Controller'
	         */
	        
    	    if(stripos($controller_name, '/') === false)
    	    {
    	        $controller_classname = $controller_name . 'Controller';
    	    }
    	    else
    	    {
    	        /*
    	         * Case of plugin controller
    	         */
    	        $controller_classname = substr($controller_name, strripos($controller_name, '/') + 1) . 'Controller';
    	    }
    	    
    	    return $controller_classname;
	    }
	    else
	    {
	        return $controller_name;
	    }
	}
	
	/****************************************************************************************/
	
	public function get_all_plugins_paths()
	{
	    $plugin_names = CakePlugin::loaded();
	    
	    $plugin_paths = array();
	    foreach($plugin_names as $plugin_name)
	    {
	        $plugin_paths[] = CakePlugin::path($plugin_name);
	    }
	    
	    return $plugin_paths;
	}
	public function get_all_plugins_names()
	{
		$plugin_names = array();
		
		$plugin_paths = $this->get_all_plugins_paths();
		foreach($plugin_paths as $plugin_path)
		{
		    $path_parts = explode('/', $plugin_path);
		    for($i = count($path_parts)-1; $i >= 0; $i--)
		    {
		        if(!empty($path_parts[$i]))
		        {
		            $plugin_names[] = $path_parts[$i];
		            break;
		        }
		    }
		}
		
		return $plugin_names;
	}
	public function get_all_plugins_controllers($filter_default_controller = false)
	{
		$plugin_paths = $this->get_all_plugins_paths();
		
		$plugins_controllers = array();
		$folder = new Folder();

		// Loop through the plugins
		foreach($plugin_paths as $plugin_path)
		{
			$didCD = $folder->cd($plugin_path . DS . 'Controller');
			
			if(!empty($didCD))
			{
				$files = $folder->findRecursive('.*Controller\.php');
				
				if(strrpos($plugin_path, DS) == strlen($plugin_path) - 1)
				{
				    $plugin_path = substr($plugin_path, 0, strlen($plugin_path) - 1);
				}
				
				$plugin_name = substr($plugin_path, strrpos($plugin_path, DS) + 1);
				
				foreach($files as $fileName)
				{
					$file = basename($fileName);
	
					// Get the controller name
					$controller_class_name = Inflector::camelize(substr($file, 0, strlen($file) - strlen('.php')));
					
					if(!$filter_default_controller || Inflector::camelize($plugin_name) . 'Controller' != $controller_class_name)
					{
					    App::uses($controller_class_name, $plugin_name . '.Controller');
					    
    					if (!preg_match('/^'. Inflector::camelize($plugin_name) . 'App/', $controller_class_name))
    					{
    					    $plugins_controllers[] = array('file' => $fileName, 'name' => Inflector::camelize($plugin_name) . "/" . substr($controller_class_name, 0, strlen($controller_class_name) - strlen('Controller')));
    					}
					}
				}
			}
		}
		
		sort($plugins_controllers);
		
		return $plugins_controllers;
	}
	public function get_all_plugins_controllers_actions($filter_default_controller = false)
	{
		$plugin_controllers = $this->get_all_plugins_controllers();
		
		$plugin_controllers_actions = array();
		
		foreach($plugin_controllers as $plugin_controller)
		{
			$plugin_name     = $this->getPluginName($plugin_controller['name']);
			$controller_name = $this->getPluginControllerName($plugin_controller['name']);
			
			if(!$filter_default_controller || $plugin_name != $controller_name)
			{
				$controller_class_name = $controller_name . 'Controller';
				
				$ctrl_cleaned_methods = $this->get_controller_actions($controller_class_name);
				
				foreach($ctrl_cleaned_methods as $action)
				{
					$plugin_controllers_actions[] = $plugin_name . '/' . $controller_name . '/' . $action;
				}
			}
		}
		
		sort($plugin_controllers_actions);
		
		return $plugin_controllers_actions;
	}
	
	public function get_all_app_controllers()
	{
		$controllers = array();
		
		App::uses('Folder', 'Utility');
		$folder = new Folder();
		
		$didCD = $folder->cd(APP . 'Controller');
		if(!empty($didCD))
		{
		    $files = $folder->findRecursive('.*Controller\.php');
		    
		    foreach($files as $fileName)
			{
				$file = basename($fileName);

				// Get the controller name
				//$controller_class_name = Inflector::camelize(substr($file, 0, strlen($file) - strlen('Controller.php')));
				
				$controller_class_name = Inflector::camelize(substr($file, 0, strlen($file) - strlen('.php')));
				App::uses($controller_class_name, 'Controller');
				
				$controllers[] = array('file' => $fileName, 'name' => substr($controller_class_name, 0, strlen($controller_class_name) - strlen('Controller')));
			}
		}
		
		sort($controllers);
		
		return $controllers;
	}
	public function get_all_app_controllers_actions()
	{
		$controllers = $this->get_all_app_controllers();
		
		$controllers_actions = array();
		
		foreach($controllers as $controller)
		{
		    $controller_class_name = $controller['name'];
		    
		    $ctrl_cleaned_methods = $this->get_controller_actions($controller_class_name);
				
			foreach($ctrl_cleaned_methods as $action)
			{
				$controllers_actions[] = $controller['name'] . '/' . $action;
			}
		}
		
		sort($controllers_actions);
		
		return $controllers_actions;
	}
	
	public function get_all_controllers()
	{
	    $app_controllers    = $this->get_all_app_controllers();
	    $plugin_controllers = $this->get_all_plugins_controllers();
	    
	    return array_merge($app_controllers, $plugin_controllers);
	}
	public function get_all_actions()
	{
	    $app_controllers_actions     = $this->get_all_app_controllers_actions();
	    $plugins_controllers_actions = $this->get_all_plugins_controllers_actions();
	    
	    return array_merge($app_controllers_actions, $plugins_controllers_actions);
	}
	
	/**
	 * Return the methods of a given class name.
	 * Depending on the $filter_base_methods parameter, it can return the parent methods.
	 *
	 * @param string $controller_class_name (eg: 'AcosController')
	 * @param boolean $filter_base_methods
	 */
	public function get_controller_actions($controller_classname, $filter_base_methods = true)
	{
	    $controller_classname = $this->get_controller_classname($controller_classname);
	    
		$methods = get_class_methods($controller_classname);
		
		if(isset($methods) && !empty($methods))
		{
    		if($filter_base_methods)
    		{
    			$baseMethods = get_class_methods('Controller');
    		
    			$ctrl_cleaned_methods = array();
    		    foreach($methods as $method)
    		    {
    		        if(!in_array($method, $baseMethods) && strpos($method, '_') !== 0)
    				{
    				    $ctrl_cleaned_methods[] = $method;
    				}
    		    }
    		    
    		    return $ctrl_cleaned_methods;
    		}
    		else
    		{
    			return $methods;
    		}
		}
		else
		{
		    return array();
		}
	}
	
}