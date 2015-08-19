<?php
/**
 * User : YuGang Yang
 * Date : 7/27/15
 * Time : 15:36
 * Email: smartydroid@gmail.com
 */

namespace Forone\Admin\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;

class BaseController extends Controller
{

    protected $currentUser;
    protected $title;
    protected $pageTitle;
    protected $rules = [];
    protected $uri = '';

    function __construct($uri='', $name='')
    {
        $this->currentUser = Auth::user();
        $this->uri = $uri;
        View::share('currentUser', $this->currentUser);

        //share the config option to all the views
        View::share('siteConfig', config('forone.site_config'));
        View::share('pageTitle', $this->loadPageTitle());
        view()->share('page_name', $name);
        view()->share('uri', $uri);
    }

    private function loadPageTitle()
    {
        $menus = config('forone.menus');
        foreach ($menus as $title => $menu) {
            if (array_key_exists('children', $menu) && $menu['children'] ) {
                foreach ($menu['children'] as $childTitle => $child) {
                    if (strripos(URL::current(), $child['uri'])) {
                        return $title;
                    }
                }
            } else {
                if (strripos(URL::current(), $menu['uri'])) {
                    return $title;
                }
            }
        }
    }

    /**
     * @param null $view
     * @param array $data
     * @param array $mergeData
     * @return View
     */
    protected function view($view = null, $data = [], $mergeData = [])
    {
        return view($view, $data, $mergeData);
    }

    /**
     * @param $error
     * @return $this
     */
    protected function redirectWithError($error)
    {
        return redirect()->to($this->getRedirectUrl())
            ->withErrors(['default' => $error]);
    }

    protected function toIndex($alert='')
    {
        $redirect = redirect()->route('admin.'.$this->uri.'.index');
        if ($alert) {
            $redirect->withErrors(['default' => $alert]);
        }
        return $redirect;
    }
}