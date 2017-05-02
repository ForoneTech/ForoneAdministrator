<?php
/**
 * User : YuGang Yang
 * Date : 7/27/15
 * Time : 15:31
 * Email: smartydroid@gmail.com
 */

namespace Forone\Controllers\Auth;


use Forone\Controllers\BaseController;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AuthController extends BaseController {

    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    use AuthenticatesAndRegistersUsers, ThrottlesLogins;

    /**
     * @var string
     */
    protected $redirectPath = 'admin/roles';

    protected $loginView = "forone::auth.login";

    /**
     * @var string
     */
    protected $redirectAfterLogout = 'admin/auth/login';

    /**
     * @var string
     */
    protected $loginPath = 'admin/auth/login';

    /**
     * Create a new authentication controller instance.
     */
    public function __construct()
    {
        parent::__construct();
        $this->middleware('guest', ['except' => 'getLogout']);
        $this->redirectPath = config('forone.RedirectAfterLoginPath') ? config('forone.RedirectAfterLoginPath') : $this->redirectPath;
    }

    /**
     * @return View
     */
    public function getLogin() {
        return view('forone::auth.login');
    }
}
