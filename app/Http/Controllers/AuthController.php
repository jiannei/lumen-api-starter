<?php


namespace App\Http\Controllers;


use App\Http\Resources\UserResource;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login']]);
    }

    public function login(Request $request)
    {
        $this->validate($request, [
            'email' => 'filled|email',
            'name' => 'required_without:email',
            'password' => 'required',
        ]);

        $credentials = request(['name', 'email', 'password']);
        if (!$token = auth()->attempt($credentials)) {
            $this->response->errorUnauthorized();
        }

        return $this->respondWithToken($token);
    }

    protected function respondWithToken($token)
    {
        return $this->response->created([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }

    public function user()
    {
        $user = auth()->userOrFail();

        return $this->response->success(new UserResource($user));
    }

    public function logout()
    {
        auth()->logout();

        return $this->response->noContent('Successfully logged out');
    }

    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }
}
