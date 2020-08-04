<?php
namespace App\Repositories\Eloquent;

use App\Repositories\Contracts\IUser;
use App\User;

class UserRepository implements IUser
{
    public function all()
    {
        return User::latest()->paginate(5);
    }
}
