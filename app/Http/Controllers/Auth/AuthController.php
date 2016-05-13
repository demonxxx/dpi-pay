<?php
namespace App\Http\Controllers\Auth;
use App\User;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
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
    protected $redirectTo = '/';
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
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
//            'username' => 'required|max:255|unique:users',
            'password' => 'required|min:6|confirmed',
            'phone_number' => 'required|min:6',
//            'user_type' => 'required|integer|between:1,2'
        ]);
    }
    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
//        $user_type = $data['user_type'];
        $max_id = User::max('id');
//        if ($user_type == SHOP_TYPE) {
//            $user_code = 'KH' . ($max_id + 1);
//        } else {
//            $user_code = 'TX' . ($max_id + 1);
//        }
        $user_code = 'KH' . ($max_id + 1);
        $user_create = User::create([
            'name'      => $data['name'],
            'email'     => $data['email'],
            'api_token' => str_random(60),
            'password'  => bcrypt($data['password']),
            'user_type' => SHOP_TYPE,
//            'username' => $data['username'],
            'phone_number' => $data['phone_number'],
            'code'      => $user_code,
        ]);
//        if ($user_type == SHOP_TYPE) {
            $user_create->roles()->sync([SHOP_TYPE]);
//        }
        return $user_create;
    }
}
