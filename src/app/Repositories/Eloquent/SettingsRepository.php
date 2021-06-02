<?php

namespace App\Repositories\Eloquent;

use App\Models\Setting;
use App\Repositories\Contracts\ISetting;

class SettingsRepository extends BaseRepository implements ISetting
{
    //returning the current model
    public function model()
    {
        return Setting::class; //this returns the model namespace - App\Model\Category
    }
}
