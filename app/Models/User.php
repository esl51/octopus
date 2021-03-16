<?php

namespace App\Models;

use App\Notifications\ResetPassword;
use App\Notifications\VerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Traits\HasRoles;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Roquie\LaravelPerPageResolver\PerPageResolverTrait;

/**
 * User
 *
 * @property string $name
 * @property string $email
 * @property string $password
 * @property string $locale
 *
 * @property Role[] $roles
 */
class User extends Authenticatable implements JWTSubject, MustVerifyEmail
{
    use Notifiable,
        HasFactory,
        HasRoles,
        ExposePermissions,
        PerPageResolverTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'locale',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'roles',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'photo_url',
    ];

    protected $guard_name = 'api';

    /**
     * Get the profile photo URL attribute.
     *
     * @return string
     */
    public function getPhotoUrlAttribute()
    {
        return vsprintf('https://www.gravatar.com/avatar/%s.jpg?s=200&d=%s', [
            md5(strtolower($this->email)),
            $this->name ? urlencode("https://ui-avatars.com/api/$this->name") : 'mp',
        ]);
    }

    public function getIsDeletableAttribute()
    {
        // if user is current user and has root role
        if ($this->id == Auth::user()->id && Auth::user()->hasRole('root')) {
            return false;
        }
        // if user has root role and current user is not root
        if ($this->hasRole('root') && !Auth::user()->hasRole('root')) {
            return false;
        }
        // if user has root role and user is last root
        if ($this->hasRole('root') && User::role('root')->count() < 2) {
            return false;
        }
        return true;
    }

    public function getIsEditableAttribute()
    {
        // if user has root role and current user is not root
        if ($this->hasRole('root') && !Auth::user()->hasRole('root')) {
            return false;
        }
        return true;
    }

    /**
     * Get the oauth providers.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function oauthProviders()
    {
        return $this->hasMany(OAuthProvider::class);
    }

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPassword($token));
    }

    /**
     * Send the email verification notification.
     *
     * @return void
     */
    public function sendEmailVerificationNotification()
    {
        $this->notify(new VerifyEmail);
    }

    /**
     * @return int
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
}
