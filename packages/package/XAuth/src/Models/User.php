<?php

namespace Package\XAuth\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Permission\Traits\HasRoles;
use Package\XAgency\Models\AgencyUser;
use Package\XAgent\Models\AgentUser;

class User extends Authenticatable implements HasMedia
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, InteractsWithMedia;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Create a new user
     *
     * @param  array  $user_data
     * @return User
     */
    public static function createUser(array $user_data): User
    {
        $user = new User;
        $user->name = $user_data['name'];
        $user->email = $user_data['email'];
        $user->password = Hash::make($user_data['password']);
        $user->save();
        return $user;
    }

    public static function getClassName()
    {
        dd(self::getClass());
    }

    public function agencyUser()
    {
        return $this->hasOne(AgencyUser::class, 'user_id');
    }

    public function agentUser()
    {
        return $this->hasOne(AgentUser::class, 'user_id');
    }

    public function avoidUnique($time)
    {
        $separator = "---";
        $this->email = $time . $separator . $this->email;
        $this->save();
    }

    protected static function newFactory(): Factory
    {
        return UserFactory::new();
    }

}
