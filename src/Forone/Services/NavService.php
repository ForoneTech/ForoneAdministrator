<?php

namespace Forone\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;

class NavService
{

    public function isActive($value)
    {
        $uri = array_key_exists('uri', $value) ? $value['uri'] : '';
        $fullUrl = URL::current();
        if (!$uri) {
            $children = $value['children'];
            foreach ($children as $child) {
                $position = strripos($fullUrl, $child['uri']);
                if ($position && substr($fullUrl, $position-1, 1) == '/') {
                    return 'active';
                }
            }
        } else if (strripos($fullUrl, $uri)) {
            return 'active';
        }
        return '';
    }

    public function checkPermission($value)
    {
        if (array_key_exists('permission', $value)) {
            return Auth::user()->can(explode('|',$value['permission']));
        }
        return true;
    }

}