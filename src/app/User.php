<?php

namespace App;

use App\Notifications\CustomEmailVerification;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Grimzy\LaravelMysqlSpatial\Eloquent\SpatialTrait;//using Spatialtrait for location

/*
* JWTSubject - Tymon JWT implementation (3rd party)
* MustVerifyEmail - Laravel's email confirmation strategy when registering a user (built-in). Using the "email_verified_at" defined below in the class and has a column in the Users table associated with it
*/
class User extends Authenticatable implements JWTSubject, MustVerifyEmail
{
    use Notifiable, SpatialTrait;

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'tagline', 'about', 'username',  'location', 'available_to_hire'
    ];

    // Spatialtrait for location
    protected $spatialFields = [
        'location'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /*
    * Overriding the "sendEmailVerificationNotification" inherited from MustVerifyEmail to our own needs
    * We are using our own Notification class - "CustomEmailVerification" created using artisan [php artisan make:CustomEmailVerification] which extends Laravel's Notification base class
    */
    public function sendEmailVerificationNotification()
    {
        $this->notify(new CustomEmailVerification);
    }
}
