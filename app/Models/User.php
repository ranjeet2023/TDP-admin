<?php

namespace App\Models;

use App\Traits\HasRecordOwnerProperties;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements HasMedia
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, InteractsWithMedia;
    use HasRecordOwnerProperties;

    /**
    * @var  string
    */
    protected $table = 'users';

    /**
    * @var  string[]
    */
    protected $fillable = [
        'username',
        'email',
        'password',
        'type',
        'mobile',
        'companyname',
        'firstname',
        'lastname',
        'email_verify_code',
        'user_type'
    ];

    /**
    * @var  string[]
    */
    protected $hidden = [
        'otp',
        'otp_attempt',
        'otp_last_attempt',
        'otp_created_at',
        'twofa_enabled',
        'password',
        'remember_token',
    ];

    /**
    * @var  string[]
    */
    protected $casts = [
        'username' => 'string',
        'password' => 'string',
        'email' => 'string',
        'name' => 'string',
        'remember_token' => 'string',
        'is_active' => 'boolean',
        'added_by' => 'integer',
        'updated_by' => 'integer',
        'login_reactive_time' => 'datetime',
        'login_retry_limit' => 'integer',
        'reset_password_expire_time' => 'datetime',
        'reset_password_code' => 'string',
        'user_type' => 'integer',
    ];

    const DEFAULT_ROLE = 'System User';

    const TYPE_USER = 1;
    const TYPE_ADMIN = 2;

    const USER_TYPE = [
        self::TYPE_USER => 'User',
        self::TYPE_ADMIN => 'Admin'
    ];

    const PLATFORM = [
        'ADMIN' => 1,
        'DEVICE' => 2,
    ];

    const USER_ROLE = [
        'USER' => 1,
        'ADMIN' => 2,
    ];

    const MAX_LOGIN_RETRY_LIMIT = 3;
    const LOGIN_REACTIVE_TIME = 20;

    const FORGOT_PASSWORD_WITH = [
        'link' => [
            'email' => true,
            'sms' => false
        ],
        'expire_time' => '20'
    ];

    const LOGIN_ACCESS = [
        'User' => [self::PLATFORM['DEVICE'],],
        'Admin' => [self::PLATFORM['ADMIN'],],
    ];

    public function routeNotificationForNexmo($notification)
    {
        return $this->phone_number; // e.g "91909945XXXX"
    }

    public function customer()
    {
        return $this->hasOne(Customer::class,'cus_id','id');
    }

    public function admins(){
        return $this->hasOne(Admin::class,'id','id');
    }

    public function staffname()
    {
        return $this->belongsTo(User::class, 'id', 'id')->select('firstname','lastname','email');
    }

    public function supplier()
    {
        return $this->hasOne(Supplier::class,'sup_id','id');
    }

    public function manager(){
        return $this->hasOne(user::class,'id','added_by');
    }


}
