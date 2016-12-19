<?php
/**
 * User : YuGang Yang
 * Date : 7/29/15
 * Time : 11:02
 * Email: smartydroid@gmail.com
 */

namespace Forone\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;

class NavService
{

    public function isActive($value)
    {
        $uri = array_key_exists('uri', $value) ? $value['uri'] : '';
        if (!$uri) {
            $children = $value['children'];
            foreach ($children as $child) {
                if (strripos(URL::current(), $child['uri'])) {
                    return 'active';
                }
            }
        } else if (strripos(URL::current(), $uri)) {
            return 'active';
        }
        return '';
    }

    public function checkPermission($value)
    {
        return array_key_exists('permission', $value) ? Auth::user()->can($value['permission']) : true;
    }

}