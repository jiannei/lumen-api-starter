<?php


namespace App\Http\Controllers;


use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UsersController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['register']]);
    }

    public function show($id)
    {
        $user = User::find($id);

        return response()->json([
            'data' => new UserResource($user),
            'message' => 'Success',
            'code' => 100001
        ]);
    }

    public function register(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
        ]);

        $user = new User();
        $user->name = $request->post('name');
        $user->email = $request->post('email');
        $user->password = Hash::make($request->post('password'));
        $user->save();

        return response()->json(['data' => $user, 'message' => 'Created', 'code' => 100001], 201);
    }
}
