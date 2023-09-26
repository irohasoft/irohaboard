<?php
App::uses("HtmlHelper", "View/Helper");

class AclHtmlHelper extends HtmlHelper
{
    var $helpers = ["Session"];

    function link($title, $url = null, $options = [], $confirmMessage = false)
    {
        $permissions = $this->Session->read("Alaxos.Acl.permissions");
        if (!isset($permissions)) {
            $permissions = [];
        }

        $aco_path = AclRouter::aco_path($url);

        if (isset($permissions[$aco_path]) && $permissions[$aco_path] == 1) {
            return parent::link($title, $url, $options, $confirmMessage);
        } else {
            return null;
        }
    }
}
