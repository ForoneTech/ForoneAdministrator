<?php namespace Forone\Controllers\Upload;
use Forone\Controllers\Controller;
use Qiniu\Auth;

/**
 * User: Mani Wang
 * Date: 8/13/15
 * Time: 10:04 PM
 * Email: mani@forone.co
 */

class QiniuController extends Controller{

    function token()
    {
        $qiniu = config('forone.qiniu');
        $bucket = $qiniu['bucket'];
        $auth = new Auth($qiniu['access_key'], $qiniu['secret_key']);

        return response()->json(['uptoken'=>$auth->uploadToken($bucket)]);
    }
}