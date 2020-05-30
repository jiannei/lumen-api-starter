<?php


namespace App\Http\Controllers;

use App\Http\Resources\UserCollection;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UsersController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['register', 'show', 'index']]);
    }

    public function index()
    {
        $resource = User::simplePaginate(15);

        return $this->response()->success(new UserCollection($resource), '成功');
    }

    public function show($id)
    {
        $user = User::findOrFail($id);

        return $this->response()->success(new UserResource($user), '成功');
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

        return $this->response()->created(new UserResource($user));
    }
}
