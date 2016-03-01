<?php
/**
 * @property AclReflectorComponent $AclReflector
 */
class AclManagerComponent extends Component
{
    var $components = array('Auth', 'Acl', 'Acl.AclReflector', 'Session');
    
    /**
     * @var AclAppController
     */
	private $controller = null;
	private $controllers_hash_file;

	/****************************************************************************************/
    
    public function initialize(Controller $controller)
	{
	    $this->controller = $controller;
	    $this->controllers_hash_file = CACHE . 'persistent' . DS . 'controllers_hashes.txt';
	}
	
	/****************************************************************************************/
	
	/**
	 * Check if the file containing the stored controllers hashes can be created,
	 * and create it if it does not exist
	 *
	 * @return boolean true if the file exists or could be created
	 */
	private function check_controller_hash_tmp_file()
	{
	    if(is_writable(dirname($this->controllers_hash_file)))
	    {
	        App :: uses('File', 'Utility');
	        $file = new File($this->controllers_hash_file, true);
	        return $file->exists();
	    }
	    else
	    {
	        $this->Session->setFlash(sprintf(__d('acl', 'the %s directory is not writable'), dirname($this->controllers_hash_file)), 'flash_error', null, 'plugin_acl');
	        return false;
	    }
	}
	
	/****************************************************************************************/
	
	public function check_user_model_acts_as_acl_requester($model_classname)
	{
//		if(!isset($this->controller->{$model_classname}))
//		{
//			/*
//			 * Do not use $this->controller->loadModel, as calling it from a plugin may prevent correct loading of behaviors
//			 */
//			$user_model = ClassRegistry :: init($model_classname);
//		}
//		else
//		{
//			$user_model = $this->controller->{$model_classname};
//		}
		
	    $user_model = $this->get_model_instance($model_classname);
	    
		$behaviors = $user_model->actsAs;
		if(!empty($behaviors) && array_key_exists('Acl', $behaviors))
		{
			$acl_behavior = $behaviors['Acl'];
			if($acl_behavior == 'requester')
			{
				return true;
			}
			elseif(is_array($acl_behavior) && isset($acl_behavior['type']) && $acl_behavior['type'] == 'requester')
			{
				return true;
			}
		}
		
		return false;
	}
	
	/**
	 * Check if a given field_expression is an existing fieldname for the given model
	 *
	 * If it doesn't exist, a virtual field called 'alaxos_acl_display_name' is created with the given expression
	 *
	 * @param string $model_classname
	 * @param string $field_expression
	 * @return string The name of the field to use as display name
	 */
	public function set_display_name($model_classname, $field_expression)
	{
	    $model_instance = $this->get_model_instance($model_classname);
	    
	    $schema = $model_instance->schema();
	    
	    if(array_key_exists($field_expression, $schema)
	        ||
	       array_key_exists(str_replace($model_classname . '.', '', $field_expression), $schema)
	        ||
	       array_key_exists($field_expression, $model_instance->virtualFields))
	    {
	        /*
	         * The field does not need to be created as it already exists in the model
	         * as a datatable field, or a virtual field configured in the model
	         */
	        
	        /*
	         * Eventually remove the model name
	         */
	        if(strpos($field_expression, $model_classname . '.') === 0)
	        {
	            $field_expression = str_replace($model_classname . '.', '', $field_expression);
	        }
	        
	        return $field_expression;
	    }
	    else
	    {
	        /*
	         * The field does not exist in the model
	         * -> create a virtual field with the given expression
	         */
	        
	        $this->controller->{$model_classname}->virtualFields['alaxos_acl_display_name'] = $field_expression;
	        
	        return 'alaxos_acl_display_name';
	    }
	}
	
	/**
	 * Return an instance of the given model name
	 *
	 * @param string $model_classname
	 * @return Model
	 */
	private function get_model_instance($model_classname)
	{
	    if(!isset($this->controller->{$model_classname}))
		{
			/*
			 * Do not use $this->controller->loadModel, as calling it from a plugin may prevent correct loading of behaviors
			 */
			$model_instance = ClassRegistry :: init($model_classname);
		}
		else
		{
			$model_instance = $this->controller->{$model_classname};
		}
		
		return $model_instance;
	}
	 
	/**
	 * return the stored array of controllers hashes
	 *
	 * @return array
	 */
	public function get_stored_controllers_hashes()
	{
	    if($this->check_controller_hash_tmp_file())
	    {
    	    $file = new File($this->controllers_hash_file);
    		$file_content = $file->read();
    		
    		if(!empty($file_content))
    		{
    			$stored_controller_hashes = unserialize($file_content);
    		}
    		else
    		{
    			$stored_controller_hashes = array();
    		}
    		
    		return $stored_controller_hashes;
	    }
	}
	
	/**
	 * return an array of all controllers hashes
	 *
	 * @return array
	 */
	public function get_current_controllers_hashes()
	{
	    $controllers = $this->AclReflector->get_all_controllers();
	    
	    $current_controller_hashes = array();
	    
	    foreach($controllers as $controller)
	    {
	        $ctler_file = new File($controller['file']);
	        $current_controller_hashes[$controller['name']] = $ctler_file->md5();
	    }
	    
	    return $current_controller_hashes;
	}
	
	/**
	 * Return ACOs paths that should exist in the ACO datatable but do not exist
	 */
	function get_missing_acos()
	{
	    $actions     = $this->AclReflector->get_all_actions();
        $controllers = $this->AclReflector->get_all_controllers();
        
        $actions_aco_paths = array();
        foreach($actions as $action)
        {
            $action_infos = explode('/', $action);
            $controller = $action_infos[count($action_infos) - 2];
            
            if($controller != 'App')
            {
                $actions_aco_paths[] = 'controllers/' . $action;
            }
        }
        foreach($controllers as $controller)
        {
            if($controller['name'] != 'App')
            {
                $actions_aco_paths[] = 'controllers/' . $controller['name'];
            }
        }
        $actions_aco_paths[] = 'controllers';
        
        $aco =& $this->Acl->Aco;
        
        $acos = array();
        
        $controllers_aco = $aco->find('first', array('fields' => array('id'), 'conditions' => array('alias' => 'controllers'), 'recursive' => -1));
        
        if(!empty($controllers_aco))
        {
            $acos = $aco->children($controllers_aco['Aco']['id'], false, 'id');
            array_unshift($acos, $controllers_aco);
        }
        
        $existing_aco_paths = array();
        foreach($acos as $aco_node)
        {
            $path_nodes = $aco->getPath($aco_node['Aco']['id']);
            $path = '';
            foreach($path_nodes as $path_node)
            {
                $path .= '/' . $path_node['Aco']['alias'];
            }
            
            $path = substr($path, 1);
            $existing_aco_paths[] = $path;
        }
        
        $missing_acos = array_diff($actions_aco_paths, $existing_aco_paths);
        
        return $missing_acos;
	}
	
	/**
	 * Store missing ACOs for all actions in the datasource
	 * If necessary, it creates actions parent nodes (plugin and controller) as well
	 */
	public function create_acos()
	{
	    $aco =& $this->Acl->Aco;
	    
	    $log = array();
	    
	    $missing_acos = $this->get_missing_acos();
	    
	    foreach($missing_acos as $missing_aco)
	    {
	        $aco_path_parts = explode('/', $missing_aco);
	        
	        $path        = '';
	        $parent_node = null;
	        
	        foreach($aco_path_parts as $aco_path_part)
	        {
	            $path .= '/' . $aco_path_part;
	            
	            $look_path = substr($path, 1);
	            
	            /*
	             * Check if the ACO exists
	             */
	            $node = $aco->node($look_path);
	            
	            if(empty($node))
	            {
	                $parent_id = null;
	                
	                if(isset($parent_node))
	                {
	                    $parent_id = isset($parent_node) ? $parent_node[0]['Aco']['id'] : null;
	                }

	                $alias = substr($path, strrpos($path, '/') + 1);
	                
	                $aco->create(array('parent_id' => $parent_id, 'model' => null, 'alias' => $alias));
	                if($aco->save())
	                {
	                    $log[] = sprintf(__d('acl', "Aco node '%s' created"), $look_path);
	                    
	                    /*
	                     * The newly created ACO node is the parent of the next ones to create (if there are some left to create)
	                     */
	                    $new_node    = $aco->findById($aco->getLastInsertID());
	                    if(!empty($new_node))
	                    {
	                        $parent_node = array($new_node);
	                    }
	                }
	            }
	            else
	            {
	                $parent_node = $node;
	            }
	        }
	    }
	    
	    return $log;
	}
	
	public function update_controllers_hash_file()
	{
	    $current_controller_hashes = $this->get_current_controllers_hashes();
	    
	    $file = new File($this->controllers_hash_file);
        $file->write(serialize($current_controller_hashes));
	}
	
	public function controller_hash_file_is_out_of_sync()
	{
	    if($this->check_controller_hash_tmp_file())
	    {
    	    $stored_controller_hashes  = $this->get_stored_controllers_hashes();
        	$current_controller_hashes = $this->get_current_controllers_hashes();
        	
        	/*
    		 * Check what controllers have changed
    		 */
    		$updated_controllers = array_keys(Set :: diff($current_controller_hashes, $stored_controller_hashes));
    		
    		return !empty($updated_controllers);
	    }
	}
	
	public function get_acos_to_prune()
	{
	    $actions     = $this->AclReflector->get_all_actions();
        $controllers = $this->AclReflector->get_all_controllers();
        $plugins     = $this->AclReflector->get_all_plugins_names();
        
        $actions_aco_paths = array();
        foreach($actions as $action)
        {
            $actions_aco_paths[] = 'controllers/' . $action;
        }
        foreach($controllers as $controller)
        {
            $actions_aco_paths[] = 'controllers/' . $controller['name'];
        }
        foreach($plugins as $plugin)
        {
            $actions_aco_paths[] = 'controllers/' . $plugin;
        }
        $actions_aco_paths[] = 'controllers';
        
        $aco =& $this->Acl->Aco;
        
        $acos = array();
        
        $controllers_aco = $aco->find('first', array('fields' => array('id'), 'conditions' => array('alias' => 'controllers'), 'recursive' => -1));
        
        if(!empty($controllers_aco))
        {
            $acos = $aco->children($controllers_aco['Aco']['id'], false, 'id');
            array_unshift($acos, $controllers_aco);
        }
        
        $existing_aco_paths = array();
        foreach($acos as $aco_node)
        {
            $path_nodes = $aco->getPath($aco_node['Aco']['id']);
            
            if(count($path_nodes) > 1 && $path_nodes[0]['Aco']['alias'] == 'controllers')
            {
                $path = '';
                foreach($path_nodes as $path_node)
                {
                    $path .= '/' . $path_node['Aco']['alias'];
                }
                
                $path = substr($path, 1);
                $existing_aco_paths[] = $path;
            }
        }
        
        $paths_to_prune = array_diff($existing_aco_paths, $actions_aco_paths);
        
        return $paths_to_prune;
	}
	
    /**
    * Remove all ACOs that don't have any corresponding controllers or actions.
    *
    * @return array log of removed ACO nodes
    */
    public function prune_acos()
    {
        $aco =& $this->Acl->Aco;
        
        $log = array();
        
        $paths_to_prune = $this->get_acos_to_prune();
        
        foreach($paths_to_prune as $path_to_prune)
        {
            $node = $aco->node($path_to_prune);
            if(!empty($node))
            {
                /*
                 * First element is the last part in path
                 * -> we delete it
                 */
                if($aco->delete($node[0]['Aco']['id']))
                {
                    $log[] = sprintf(__d('acl', "Aco node '%s' has been deleted"), $path_to_prune);
                }
                else
                {
                    $log[] = '<span class="error">' . sprintf(__d('acl', "Aco node '%s' could not be deleted"), $path_to_prune) . '</span>';
                }
            }
        }
        
        return $log;
    }
	
	/**
	 *
	 * @param AclNode $aro_nodes The Aro model hierarchy
	 * @param string $aco_path The Aco path to check for
	 * @param string $permission_type 'deny' or 'allow', 'grant', depending on what permission (grant or deny) is being set
	 */
	public function save_permission($aro_nodes, $aco_path, $permission_type)
	{
	    if(isset($aro_nodes[0]))
	    {
	        $aco_path = 'controllers/' . $aco_path;
	        
	        $pk_name = 'id';
	        if($aro_nodes[0]['Aro']['model'] == Configure :: read('acl.aro.role.model'))
	        {
	            $pk_name = $this->controller->_get_role_primary_key_name();
	        }
	        elseif($aro_nodes[0]['Aro']['model'] == Configure :: read('acl.aro.user.model'))
	        {
	            $pk_name = $this->controller->_get_user_primary_key_name();
	        }
	        
	        $aro_model_data = array($aro_nodes[0]['Aro']['model'] => array($pk_name => $aro_nodes[0]['Aro']['foreign_key']));
	        $aro_id         = $aro_nodes[0]['Aro']['id'];
    	    
	    	$specific_permission_right  = $this->get_specific_permission_right($aro_nodes[0], $aco_path);
	    	$inherited_permission_right = $this->get_first_parent_permission_right($aro_nodes[0], $aco_path);
	    	
	    	if(!isset($inherited_permission_right) && count($aro_nodes) > 1)
	    	{
	    	    /*
	    	     * Get the permission inherited by the parent ARO
	    	     */
	    	    $specific_parent_aro_permission_right = $this->get_specific_permission_right($aro_nodes[1], $aco_path);
	    	    
	    	    if(isset($specific_parent_aro_permission_right))
	    	    {
	    	        /*
	    	         * If there is a specific permission for the parent ARO on the ACO, the child ARO inheritates this permission
	    	         */
	    	        $inherited_permission_right = $specific_parent_aro_permission_right;
	    	    }
	    	    else
	    	    {
	    	        $inherited_permission_right = $this->get_first_parent_permission_right($aro_nodes[1], $aco_path);
	    	    }
	    	}
	        
	    	/*
    	     * Check if the specific permission is necessary to get the correct permission
    	     */
	    	if(!isset($inherited_permission_right))
	    	{
	    	    $specific_permission_needed = true;
	    	}
	    	else
	    	{
        	    if($permission_type == 'allow' || $permission_type == 'grant')
        	    {
        	        $specific_permission_needed = ($inherited_permission_right != 1);
        	    }
        	    else
        	    {
        	        $specific_permission_needed = ($inherited_permission_right == 1);
        	    }
	    	}
    	    
    	    if($specific_permission_needed)
    	    {
    	        if($permission_type == 'allow' || $permission_type == 'grant')
    	        {
        	        if($this->Acl->allow($aro_model_data, $aco_path))
        	        {
        	            return true;
        	        }
        	        else
        	        {
        	            trigger_error(__d('acl', 'An error occured while saving the specific permission'), E_USER_NOTICE);
        	            return false;
        	        }
    	        }
    	        else
    	        {
    	            if($this->Acl->deny($aro_model_data, $aco_path))
        	        {
        	            return true;
        	        }
        	        else
        	        {
        	            trigger_error(__d('acl', 'An error occured while saving the specific permission'), E_USER_NOTICE);
        	            return false;
        	        }
    	        }
    	    }
    	    elseif(isset($specific_permission_right))
    	    {
    	        $aco_node = $this->Acl->Aco->node($aco_path);
            	if(!empty($aco_node))
            	{
            	    $aco_id = $aco_node[0]['Aco']['id'];
            	    
        	        $specific_permission = $this->Acl->Aro->Permission->find('first', array('conditions' => array('aro_id' => $aro_id, 'aco_id' => $aco_id)));
        	        
        	        if(!empty($specific_permission))
        	        {
        	            if($this->Acl->Aro->Permission->delete(array('Permission.id' => $specific_permission['Permission']['id'])))
        	            {
        	                return true;
        	            }
        	            else
        	            {
        	                trigger_error(__d('acl', 'An error occured while deleting the specific permission'), E_USER_NOTICE);
        	                return false;
        	            }
        	        }
        	        else
        	        {
        	            /*
        	             * As $specific_permission_right has a value, we should never fall here, but who knows... ;-)
        	             */
        	            
        	            trigger_error(__d('acl', 'The specific permission id could not be retrieved'), E_USER_NOTICE);
        	            return false;
        	        }
            	}
            	else
            	{
            	    /*
    	             * As $specific_permission_right has a value, we should never fall here, but who knows... ;-)
    	             */
            	    trigger_error(__d('acl', 'The child ACO id could not be retrieved'), E_USER_NOTICE);
        	        return false;
            	}
    	    }
    	    else
    	    {
    	        /*
    	         * Right can be inherited, and no specific permission exists => there is nothing to do...
    	         */
    	    }
	    }
	    else
	    {
	        trigger_error(__d('acl', 'Invalid ARO'), E_USER_NOTICE);
	        return false;
	    }
	}
	
	private function get_specific_permission_right($aro_node, $aco_path)
	{
	    $pk_name = 'id';
        if($aro_node['Aro']['model'] == Configure :: read('acl.aro.role.model'))
        {
            $pk_name = $this->controller->_get_role_primary_key_name();
        }
        elseif($aro_node['Aro']['model'] == Configure :: read('acl.aro.user.model'))
        {
            $pk_name = $this->controller->_get_user_primary_key_name();
        }
	    
	    $aro_model_data = array($aro_node['Aro']['model'] => array($pk_name => $aro_node['Aro']['foreign_key']));
    	$aro_id         = $aro_node['Aro']['id'];
    	
    	/*
    	 * Check if a specific permission of the ARO's on the ACO already exists in the datasource
    	 * =>
    	 * 		1) the ACO node must exist in the ACO table
    	 * 		2) a record with the aro_id and aco_id must exist in the aros_acos table
    	 */
    	$aco_id                    = null;
    	$specific_permission       = null;
    	$specific_permission_right = null;
    	
    	$aco_node = $this->Acl->Aco->node($aco_path);
    	if(!empty($aco_node))
    	{
    	    $aco_id = $aco_node[0]['Aco']['id'];
    	    
    	    $specific_permission = $this->Acl->Aro->Permission->find('first', array('conditions' => array('aro_id' => $aro_id, 'aco_id' => $aco_id)));
    	    
    	    if(!empty($specific_permission))
    	    {
    	        /*
    	         * Check the right (grant => true / deny => false) of this specific permission
    	         */
    	        $specific_permission_right = $this->Acl->check($aro_model_data, $aco_path);
    	        
    	        if($specific_permission_right)
    	        {
    	            return 1;    // allowed
    	        }
    	        else
    	        {
    	            return -1;    // denied
    	        }
    	    }
    	}
    	
    	return null; // no specific permission found
	}
	
	private function get_first_parent_permission_right($aro_node, $aco_path)
	{
	    $pk_name = 'id';
        if($aro_node['Aro']['model'] == Configure :: read('acl.aro.role.model'))
        {
            $pk_name = $this->controller->_get_role_primary_key_name();
        }
        elseif($aro_node['Aro']['model'] == Configure :: read('acl.aro.user.model'))
        {
            $pk_name = $this->controller->_get_user_primary_key_name();
        }
        
	    $aro_model_data = array($aro_node['Aro']['model'] => array($pk_name => $aro_node['Aro']['foreign_key']));
    	$aro_id         = $aro_node['Aro']['id'];
    	
    	while(strpos($aco_path, '/') !== false && !isset($parent_permission_right))
        {
            $aco_path = substr($aco_path, 0, strrpos($aco_path, '/'));
            
	        $parent_aco_node = $this->Acl->Aco->node($aco_path);
	    	if(!empty($parent_aco_node))
	    	{
	    	    $parent_aco_id = $parent_aco_node[0]['Aco']['id'];
	    	    
    	    	$parent_permission = $this->Acl->Aro->Permission->find('first', array('conditions' => array('aro_id' => $aro_id, 'aco_id' => $parent_aco_id)));
	    	    
    	    	if(!empty($parent_permission))
	    	    {
	    	        /*
	    	         * Check the right (grant => true / deny => false) of this first parent permission
	    	         */
	    	        $parent_permission_right = $this->Acl->check($aro_model_data, $aco_path);
	    	        
    	    	    if($parent_permission_right)
        	        {
        	            return 1;    // allowed
        	        }
        	        else
        	        {
        	            return -1;    // denied
        	        }
	    	    }
	    	}
        }
        
        return null; // no parent permission found
	}

	/**
	 * Set the permissions of the authenticated user in Session
	 * The session permissions are then used for instance by the AclHtmlHelper->link() function
	 */
	public function set_session_permissions()
    {
        if(!$this->Session->check('Alaxos.Acl.permissions'))
        {
            $actions = $this->AclReflector->get_all_actions();
            
            $user = $this->Auth->user();
            
            if(!empty($user))
            {
                $user = array(Configure :: read('acl.aro.user.model') => $user);
                $permissions = array();
            
                foreach($actions as $action)
                {
                    $aco_path = 'controllers/' . $action;
                    
                    $permissions[$aco_path] = $this->Acl->check($user, $aco_path);
                }
                
                $this->Session->write('Alaxos.Acl.permissions', $permissions);
            }
        }
    }
}