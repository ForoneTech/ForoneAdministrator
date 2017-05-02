<?php
/**
 * User : YuGang Yang
 * Date : 7/27/15
 * Time : 15:33
 * Email: smartydroid@gmail.com
 */

namespace Forone\Controllers;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as Base2Controller;

abstract class Controller extends Base2Controller
{
    use DispatchesJobs, ValidatesRequests;
}