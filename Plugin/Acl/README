ACL Plugin for CakePHP 2.0
===========================

Version: 2.3.0
Date: 2013-05-02
Author: Nicolas Rod <nico@alaxos.com>
Website: http://www.alaxos.net/blaxos/pages/view/plugin_acl_2.0
License: http://www.opensource.org/licenses/mit-license.php The MIT License

This CakePHP plugin is an interface to manage an ACL protected web application.

It is made to work with an application already configured with ACL. The documentation to configure the ACL 
can be found on the following links :

- http://book.cakephp.org/2.0/en/core-libraries/components/access-control-lists.html
- http://book.cakephp.org/2.0/en/tutorials-and-examples/simple-acl-controlled-application/simple-acl-controlled-application.html


Installation
-------------


- Download the plugin and put it in your 'app/Plugin' or 'plugins' folder
- Configure the 'admin' route (see http://book.cakephp.org/2.0/en/development/routing.html)
- Configure the plugin according to your web application:

	Some settings have to be read by CakePHP when the plugin is loaded. They can be found
	in the 'Acl/Config/bootstrap.php' file.
	
	You have two options to use these settings, as CakePHP doesn't automatically load 
	the bootstrap.php files in plugins:
	
	1.	Copy all the settings in your app/config/bootstrap.php file
	
	or
	
	2.	Load the plugin bootstrap with the plugin
	
	        CakePlugin::load('Acl', array('bootstrap' => true));

- Make sure the AclComponent is declared in your AppController and that your User and Group/Role models are configured to act as requester (see the CakePHP ACL tutorial)  
- Access the ACL plugin by navigating to /admin/acl


About CakePHP 2.0
-----------------

If you use CakePHP version 2.0, the plugin will work only if you do not use the E_STRICT error reporting level.

You can use E_STRICT error reporting level with CakePHP 2.1 or above.
 
