<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Grimzy\LaravelMysqlSpatial\Types\Point;

class UserSettingController extends Controller
{
    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        $this->validate($request, [
            'tagline' => 'required',
            'name' => 'required',
            'about' => 'required|string|min:10',
            'location.latitude' => 'required|numeric|between:-90,90',
            'location.longitude' => 'required|numeric|between:-180,180',
        ]);

        $location = new Point($request->location['latitude'], $request->location['longitude']);

        $user->update([
            'name' => $request->name,
            'location' => $location,
            'about' => $request->about,
            'tagline' => $request->tagline
        ]);

        return new UserResource($user);

    }

    public function updatePassword(Request $request)
    {

    }
}
