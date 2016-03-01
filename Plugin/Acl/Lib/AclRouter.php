<?php
class AclRouter
{
    /**
     * Get the ACO path of a link.
     * This can be useful for example to check then if a user is allowed to access an url.
     *
     * @param mixed $url Cake-relative URL, like "/products/edit/92" or "/presidents/elect/4"
     *   or an array specifying any of the following: 'controller', 'action',
     *   and/or 'plugin', in addition to named arguments (keyed array elements),
     *   and standard URL arguments (indexed array elements)
     */
    static function aco_path($url)
    {
        $r_url      = Router::url($url);
        $r_url      = str_replace(Router::url('/'), '/', $r_url);
        $parsed_url = Router::parse($r_url);
        
        $aco_path = 'controllers/';
        
        if(!empty($parsed_url['plugin']))
        {
            $aco_path .= Inflector::camelize($parsed_url['plugin']) . '/';
        }
        
        $aco_path .= Inflector::camelize($parsed_url['controller']) . '/';
        $aco_path .= $parsed_url['action'];
        
        return $aco_path;
    }
}