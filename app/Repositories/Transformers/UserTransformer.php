<?php


namespace App\Repositories\Transformers;

use App\Repositories\Models\User;
use League\Fractal\TransformerAbstract;

class UserTransformer extends TransformerAbstract
{
    public function transform(User $user)
    {
        return [
            'nickname' => $user->name,
            'email' => $user->email,
        ];
    }
}
