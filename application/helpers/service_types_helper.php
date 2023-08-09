<?php
/**
 * Service types helper
 * @created 2023-04-27
 *
 * @description
 *
 * @usage
 * Include using
 *  $this->load->helper('service_types_helper');
 *
 */
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

function getServiceTypeAllowedColors()
{
    return array("#9b2226"=>"Dark Red",
                "#ae2012"=>"Red",
                "#bb3e03"=>"Orange",
                "#ca6702"=>"Light Orange",
                "#ee9b00"=>"Yellow",
                "#e9d8a6"=>"Gray",
                "#94d2bd"=>"Light Green",
                "#0a9396"=>"Green",
                "#005f73"=>"Dark Green",
                "#001219"=>"Dark Blue",
                );
}