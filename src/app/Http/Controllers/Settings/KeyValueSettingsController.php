<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Http\Resources\SettingsResource;
use App\Models\Setting;
use App\Repositories\Contracts\ISetting;
use Illuminate\Http\Request;
use App\Repositories\Eloquent\Criteria\LatestFirst;
use Illuminate\Http\Response;

class KeyValueSettingsController extends Controller
{
    protected $settings;

    public function __construct(ISetting $settings)
    {
        $this->settings = $settings;
        $this->middleware('auth');
    }

    public function index()
    {
        $settings =  $this->settings->withCriteria([])->all();

        return SettingsResource::collection($settings);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'key' => ['required','unique:settings,key', 'min:4'],
            'value' => ['required']
        ]);

        $resource = $this->settings->create($request->all());
        return response(new SettingsResource($resource), Response::HTTP_CREATED);
    }

    public function update(Request $request, Setting $article)
    {
    }
}
