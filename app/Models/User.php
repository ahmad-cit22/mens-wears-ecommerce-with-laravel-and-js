<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

use DB;

class User extends Authenticatable {
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
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

    public static function getPermissionGroups() {
        $permissionGroups = DB::table('permissions')->select('group_name')->groupBy('group_name')->get();
        return $permissionGroups;
    }
    public static function permissionsByGroupName($groupname) {
        $permissions = DB::table('permissions')->where('group_name', $groupname)->get();
        return $permissions;
    }

    public function orders() {
        return $this->hasMany(Order::class, 'customer_id')->with('customer', 'customer.member', 'customer.member.card', 'area', 'district')->orderBy('created_at', 'desc');
    }

    public function is_customer() {
        return $this->orders == null ? false : true;
    }

    public function member() {
        return $this->hasOne(Member::class, 'user_id');
    }

    public function vendor() {
        return $this->hasOne(Vendor::class, 'user_id');
    }

    public function district() {
        return $this->belongsTo(District::class, 'city');
    }
}
