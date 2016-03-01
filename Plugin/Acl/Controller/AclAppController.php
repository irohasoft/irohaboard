<?php
/**
 *
 * @author   Nicolas Rod <nico@alaxos.com>
 * @license  http://www.opensource.org/licenses/mit-license.php The MIT License
 * @link     http://www.alaxos.ch
 *
 * @property AclManagerComponent $AclManager
 */
class AclAppController extends AppController
{
    var $components = array('RequestHandler', 'Acl.AclManager', 'Acl.AclReflector');
	
    function beforeFilter()
	{
	    parent :: beforeFilter();
	    
		$this->_check_config();
		$this->_check_files_updates();
	}
    
	private function _check_config()
	{
	    $role_model_name = Configure :: read('acl.aro.role.model');
	    
		if(!empty($role_model_name))
		{
	    	$this->set('role_model_name',    $role_model_name);
	    	$this->set('user_model_name',    Configure :: read('acl.aro.user.model'));
	    	$this->set('role_pk_name',       $this->_get_role_primary_key_name());
	    	$this->set('user_pk_name',       $this->_get_user_primary_key_name());
	    	$this->set('role_fk_name',       $this->_get_role_foreign_key_name());
	    	
	    	$this->_authorize_admins();
	    	
//	    	if($this->name != 'Acl'
//	    		&&
//	    	   ($this->name != 'Acos' || $this->action != 'admin_build_acl')
//	    	  )
//	    	{
//	    	    $missing_aco_nodes = $this->AclManager->get_missing_acos();
//
//		    	if(count($missing_aco_nodes) > 0)
//	    		{
//	    		    $this->set('missing_aco_nodes', $missing_aco_nodes);
//	    		    $this->render('/Acos/admin_acos_missing');
//	    		}
//	    	}
	    	
	    	if(Configure :: read('acl.check_act_as_requester'))
	    	{
	    		$is_requester = true;
	    		
	    		if(!$this->AclManager->check_user_model_acts_as_acl_requester(Configure :: read('acl.aro.user.model')))
	    		{
	    			$this->set('model_is_not_requester', false);
	    			$is_requester = false;
	    		}
	    		
	    		if(!$this->AclManager->check_user_model_acts_as_acl_requester(Configure :: read('acl.aro.role.model')))
	    		{
	    			$this->set('role_is_not_requester', false);
	    			$is_requester = false;
	    		}
	    		
	    		if(!$is_requester)
	    		{
	    			$this->render('/Aros/admin_not_acl_requester');
	    		}
	    	}
		}
		else
		{
			$this->Session->setFlash(__d('acl', 'The role model name is unknown. The ACL plugin bootstrap.php file has to be loaded in order to work. (see the README file)'), 'flash_error', null, 'plugin_acl');
		}
	}
	
	function _check_files_updates()
	{
	    if($this->request->params['controller'] != 'acos'
	        || ($this->request->params['action'] != 'admin_synchronize' &&
	            $this->request->params['action'] != 'admin_prune_acos' &&
	            $this->request->params['action'] != 'admin_build_acl'))
	    {
    	    if($this->AclManager->controller_hash_file_is_out_of_sync())
    	    {
    	        $missing_aco_nodes = $this->AclManager->get_missing_acos();
    	        $nodes_to_prune    = $this->AclManager->get_acos_to_prune();
    	        
    	        $has_updates = false;
    	        
    	        if(count($missing_aco_nodes) > 0)
        		{
        		    $has_updates = true;
        		}
        		
        		if(count($nodes_to_prune) > 0)
        		{
        		    $has_updates = true;
        		}
        		
        		$this->set('nodes_to_prune', $nodes_to_prune);
        		$this->set('missing_aco_nodes', $missing_aco_nodes);
        		
        		if($has_updates)
        		{
        		    $this->render('/Acos/admin_has_updates');
        		    $this->response->send();
        		    $this->AclManager->update_controllers_hash_file();
        		    die();
        		}
        		else
        		{
        		    $this->AclManager->update_controllers_hash_file();
        		}
    	    }
	    }
	}
	
	private function _authorize_admins()
	{
		$authorized_role_ids = Configure :: read('acl.role.access_plugin_role_ids');
		$authorized_user_ids = Configure :: read('acl.role.access_plugin_user_ids');

		$model_role_fk = $this->_get_role_foreign_key_name();
		
	    if(in_array($this->Auth->user($model_role_fk), $authorized_role_ids)
	       || in_array($this->Auth->user($this->_get_user_primary_key_name()), $authorized_user_ids))
	    {
	        // Allow all actions. CakePHP 2.0
            $this->Auth->allow('*');
            
            // Allow all actions. CakePHP 2.1
            $this->Auth->allow();
	    }
	}
	
    function _get_passed_aco_path()
	{
	    $aco_path  = isset($this->params['named']['plugin']) ? $this->params['named']['plugin'] : '';
        $aco_path .= empty($aco_path) ? $this->params['named']['controller'] : '/' . $this->params['named']['controller'];
        $aco_path .= '/' . $this->params['named']['action'];
        
        return $aco_path;
	}
	function _set_aco_variables()
	{
        $this->set('plugin', isset($this->params['named']['plugin']) ? $this->params['named']['plugin'] : '');
        $this->set('controller_name', $this->params['named']['controller']);
        $this->set('action', $this->params['named']['action']);
	}
	
	function _get_role_primary_key_name()
	{
	    $forced_pk_name = Configure :: read('acl.aro.role.primary_key');
	    if(!empty($forced_pk_name))
	    {
	        return $forced_pk_name;
	    }
	    else
	    {
	        /*
	         * Return the primary key's name that follows the CakePHP conventions
	         */
	        return 'id';
	    }
	}
	function _get_user_primary_key_name()
	{
	    $forced_pk_name = Configure :: read('acl.aro.user.primary_key');
	    if(!empty($forced_pk_name))
	    {
	        return $forced_pk_name;
	    }
	    else
	    {
	        /*
	         * Return the primary key's name that follows the CakePHP conventions
	         */
	        return 'id';
	    }
	}
	function _get_role_foreign_key_name()
	{
	    $forced_fk_name = Configure :: read('acl.aro.role.foreign_key');
	    if(!empty($forced_fk_name))
	    {
	        return $forced_fk_name;
	    }
	    else
	    {
	        /*
	         * Return the foreign key's name that follows the CakePHP conventions
	         */
	        return Inflector :: underscore(Configure :: read('acl.aro.role.model')) . '_id';
	    }
	}
	
	function _return_to_referer()
	{
	    $this->redirect($this->referer(array('action' => 'admin_index')));
	}
}
?>