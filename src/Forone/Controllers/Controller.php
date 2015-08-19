<?php
/**
 * User : YuGang Yang
 * Date : 7/27/15
 * Time : 15:33
 * Email: smartydroid@gmail.com
 */

namespace Forone\Admin\Controllers;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

abstract class Controller extends BaseController
{
    use DispatchesJobs, ValidatesRequests;
}