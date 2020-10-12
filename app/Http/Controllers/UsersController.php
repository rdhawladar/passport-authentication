<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Laravel\Socialite\Facades\Socialite;

class UsersController extends Controller
{
    public function index() {
        dd('login');
    }
    public function redirectToProvider()
    {
        return Socialite::driver('github')->redirect();
    }
    public function handleProviderCallback()
    {
        $user = Socialite::driver('github')->user();
        dd($user);

        // $user->token;
    }
    public function login()
    {
        if (Auth::attempt(['email' => request('email'), 'password' => request('password')])) {
            $user = Auth::user();
            $success['token'] = $user->createToken('appToken')->accessToken;
            // $success['token'] = $user->createToken('appToken')->refreshToken;
            //After successfull authentication, notice how I return json parameters
            return response()->json([
                'success' => true,
                'token' => $success,
                'user' => $user
            ]);
        } else {
            //if authentication is unsuccessfull, notice how I return json parameters
            return response()->json([
                'success' => false,
                'message' => 'Invalid Email or Password',
            ], 401);
        }
    }

    /**
     * Register api.
     *
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors(),
            ], 401);
        }
        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        $success['token'] = $user->createToken('appToken')->accessToken;
        return response()->json([
            'success' => true,
            'token' => $success,
            'user' => $user
        ]);
    }

    public function logout(Request $request)
    {
        if (Auth::user()) {
            $user = Auth::user()->token();
            $user->revoke();

            return response()->json([
                'success' => true,
                'message' => 'Logout successfully'
            ]);
        }
        
        return response()->json([
            'success' => false,
            'message' => 'Unable to Logout'
        ]);


        dump(Auth::user());

        $http = new \GuzzleHttp\Client;
        try {
            $response = $http->post('http://laravel-passport.local/oauth/token', [
                'form_params' => [
                    'grant_type' => 'social',
                    'client_id' => 1,
                    'client_secret' => '7DZ1nwlgJuJhAHnKdwJrCXOqchQOJobHQkNBC3Pw',
                    /* 'client_id' => 'ffb0dd84e919d5a4b50b',
                    'client_secret' => 'f4591adf2f4e5cc042df725a30b3770db9ab688f', */
                    'provider' => 'github',
                    'access_token' => '8aae2ec3ff211bf7d050a5b785282cec69963cb6',
                ],
            ]);

        } catch(\Throwable $e) {
            dump('adf');
            $response = $e;

        }
        dd($response);

        dd(json_decode((string) $response->getBody(), true));



        $http = new \GuzzleHttp\Client;
        $response = $http->post('http://laravel-passport.local/oauth/token', [
            'form_params' => [
                'grant_type' => 'password',
                'client_id' => 2,
                'client_secret' => 'ZmaDVHbZfd63Wj3qmr4DLFfVrSdEzj1gQLApBOPc',
                'username' => 'rdhawladar@gmail.com',
                'password' => 123456,
                'scope' => '',
            ],
        ]);
        dd(json_decode((string) $response->getBody(), true));

        return json_decode((string) $response->getBody(), true);



        dump(Auth::user());
        dd(Auth::user()->token());
        dd($res);

        dd('logout controller...');

        if (Auth::user()) {
            $user = Auth::user()->token();
            $user->revoke();

            return response()->json([
                'success' => true,
                'message' => 'Logout successfully'
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Unable to Logout'
            ]);
        }
    }

    public function redirect(Request $request) {
        dd($request);
        $query = http_build_query([
            'client_id' => 1,
            'redirect_uri' => 'http://laravel-passport.local/callback',
            'response_type' => 'code',
            'scope' => '',
            // 'state' => 0,
        ]);
        return redirect('http://laravel-passport.local/oauth/authorize?'.$query);
        
    }
    public function callback(Request $request) {
        dd($request);
    }


}






/* 

    public $successStatus = 200;

    public function login() { 
        if (Auth::attempt(['email' => request('email'), 'password' => request('password')])) { 
            $oClient = OClient::where('password_client', 1)->first();
            return $this->getTokenAndRefreshToken($oClient, request('email'), request('password'));
        } 
        else { 
            return response()->json(['error'=>'Unauthorised'], 401); 
        } 
    }

    public function register(Request $request) { 
        $validator = Validator::make($request->all(), [ 
            'name' => 'required', 
            'email' => 'required|email|unique:users', 
            'password' => 'required', 
            'c_password' => 'required|same:password', 
        ]);

        if ($validator->fails()) { 
            return response()->json(['error'=>$validator->errors()], 401);            
        }

        $password = $request->password;
        $input = $request->all(); 
        $input['password'] = bcrypt($input['password']); 
        $user = User::create($input); 
        $oClient = OClient::where('password_client', 1)->first();
        return $this->getTokenAndRefreshToken($oClient, $user->email, $password);
    }

    public function getTokenAndRefreshToken(OClient $oClient, $email, $password) { 
        $oClient = OClient::where('password_client', 1)->first();
        $http = new Client;
        $response = $http->request('POST', 'http://mylemp-nginx/oauth/token', [
            'form_params' => [
                'grant_type' => 'password',
                'client_id' => $oClient->id,
                'client_secret' => $oClient->secret,
                'username' => $email,
                'password' => $password,
                'scope' => '*',
            ],
        ]);

        $result = json_decode((string) $response->getBody(), true);
        return response()->json($result, $this->successStatus);
    }
 */