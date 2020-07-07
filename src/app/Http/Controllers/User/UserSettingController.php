<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Rules\CheckSamePassword;
use App\Rules\MatchOldPassword;
use Illuminate\Http\Request;
use Grimzy\LaravelMysqlSpatial\Types\Point;

class UserSettingController extends Controller
{
    public function updateProfile(Request $request)
    {
        //$user = auth()->user(); //NOT required since this route is protected by "auth:api" middleware. See /routes/api.php

        $this->validate($request, [
            'tagline' => 'required',
            'name' => 'required',
            'about' => 'required|string|min:10',
            'location.latitude' => 'required|numeric|between:-90,90',
            'location.longitude' => 'required|numeric|between:-180,180',
        ]);

        $location = new Point($request->location['latitude'], $request->location['longitude']);

        $request->user()->update([
            'name' => $request->name,
            'location' => $location,
            'about' => $request->about,
            'tagline' => $request->tagline
        ]);

        return new UserResource($request->user());

    }

    public function updatePassword(Request $request)
    {
        //$user = auth()->user(); //NOT required since this route is protected by "auth:api" middleware. See /routes/api.php

        $this->validate($request, [
            'current_password' => ['required', new MatchOldPassword],
            'password' => ['required', 'confirmed','min:6', new CheckSamePassword],
        ]);

        $request->user()->update([
            'password' => bcrypt($request->password)
        ]);

        return response()->json(['message' => 'password updated'], 200);
    }
}
