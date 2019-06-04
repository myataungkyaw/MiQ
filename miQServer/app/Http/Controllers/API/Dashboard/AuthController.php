<?php

namespace App\Http\Controllers\API\Dashboard;

use App\Models\Dashboard\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use JWTAuth;

class AuthController extends Controller
{
    /**
     * AuthController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login']]);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function login()
    {
        $credentials = request(['email', 'password']);

        if (! $token = JWTAuth::attempt($credentials)) {
            return response()->json(['error' => 'Your password is incorrect!'], 401);
        }

        return $this->respondWithToken($token);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    // public function me()
    // {
    //     return response()->json(auth()->user());
    // }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * @return mixed
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }


    public function me(Request $request)
    {
        $user = null;

        try {

            if (! $user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['user_not_found'], 404);
            }

        } catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {

            return response()->json(['token_expired'], $e->getStatusCode());

        } catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {

            return response()->json(['token_invalid'], $e->getStatusCode());

        } catch (Tymon\JWTAuth\Exceptions\JWTException $e) {

            return response()->json(['token_absent'], $e->getStatusCode());

        }

        
        // the token is valid and we have found the user via the sub claim
        return response()->json(compact('user'));
    }


    /**
     * @param $token
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        $user = Auth::user();
        $userCompanies =  User::find($user->id)->companies;
        foreach($userCompanies as $company){
            if($company->logo){
            $company->logo = url('storage/uploads/companies/'.$company->logo);
            }

            if($company->background_image){
                $company->background_image = url('storage/uploads/backgrounds/'.$company->background_image);
            }

            if($company->notification_sound){
                $company->notification_sound = url('storage/uploads/sounds/'.$company->notification_sound);
            }
            
        }

        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'user'=> $user,
            'user_companies'=> $userCompanies,
            'expires_in' => auth('api')->factory()->getTTL() * 60
        ]);
    }



}
