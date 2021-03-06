<?php
namespace App\Http\Controllers\Auth;

use App\User;
use App\Shop;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use App\Account;

class AuthController extends Controller
{
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
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = '/admin';

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware($this->guestMiddleware(), ['except' => 'logout']);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name'         => 'required|max:255',
            'email'        => 'required|email|max:255|unique:users',
            'password'     => 'required|min:6|confirmed',
            'phone_number' => 'required|min:6|unique:users',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array $data
     * @return User
     */
    protected function create(array $data)
    {
        $max_id = User::max('id');
        $user_code = 'U' . ($max_id + 1);
        $user_create = User::create([
            'name'         => $data['name'],
            'email'        => $data['email'],
            'api_token'    => str_random(60),
            'password'     => bcrypt($data['password']),
            'user_type'    => SHOP_TYPE,
            'phone_number' => $data['phone_number'],
            'ios_device_token' => empty($data['ios_device_token'])? null : $data['ios_device_token'],
            'gcm_id' => empty($data['gcm_id'])? null : $data['gcm_id'],
            'code'         => $user_code,
        ]);
        $shop = new Shop;
        $max_shop_id = Shop::max('id');
        $shop->code = 'KH'.($max_shop_id + 1);
        $user_create->shop()->save($shop);
        $user_create->roles()->sync([SHOP_TYPE]);
        $account = new Account();
        $account->user_id = $user_create->id;
        $account->save();
        return $user_create;
    }
}
