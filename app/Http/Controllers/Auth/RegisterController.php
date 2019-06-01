<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Notifications\RegisterUser;
use App\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
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
            'name' => 'required|string|max:255',
            'forname' => 'sometimes|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'avatar' => 'sometimes|image|max:1000' , 
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {

        $usr = [
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'confirmation_token' => str_replace('/', '' , bcrypt(str_random(16)) ),
        ];

        if (isset($data['avatar'])) {
            $usr['avatar'] = $data['avatar'] ; 
        }

        if (isset($data['forname'])) {
            $usr['forname'] = $data['forname'] ; 
        }

        return User::create( $usr ) ;

    }


    public function register(Request $request)
    {
        
        $all = $request->all() ; 

        $validator = $this->validator( $all );

        if ($validator->fails()) {
           return $this->errorJson($validator->errors());
        }


        $file = $request->file('avatar') ; 
        
        if ( $file ) {
            $filei = $file->storePublicly('files',['disk'=>env('FILE_DRIVER')]);
            $avatar = Storage::disk(env('FILE_DRIVER'))->url('/files/'.basename($filei));
            $all['avatar'] = $avatar ; 
        }

        event(new Registered($user = $this->create( $all )));

        $user->notify(new RegisterUser($user)) ; 

        //@Todo::Lang
        return $this->successJson('success inscription');

    }

    /*
    *   Confirmation de l'inscription par email
    */

    public function confirm( Request $request , $id , $confirmation_token )
    {
        $user = User::Where('id',$id)->Where('confirmation_token',$confirmation_token)->first();

        if ( $user ) {
            $user->update(['confirmation_token'=>null]) ; 
            $this->guard()->login($user) ; 
            return redirect( $this->redirectPath() );
        }else{
            return redirect('/login') -> with('error','ce lien ne semble plus valide'); 
        }

    }

}
