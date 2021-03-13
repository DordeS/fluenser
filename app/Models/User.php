<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getAccountInfoByUserID($user_id) {
        $accountInfo = DB::table('users')
                    ->where('users.id', $user_id)
                    ->join('influencers', 'users.id', '=', 'influencers.user_id')
                    ->join('influencers_info', 'influencers.id','=','influencers_info.influencer_id')
                    ->select('users.name', 'users.email', 'influencers_info.*')
                    ->get();
        if(count($accountInfo) == 0){
            $accountInfo = DB::table('users')
                    ->where('users.id', $user_id)
                    ->join('brands', 'users.id', '=', 'brands.user_id')
                    ->join('brand_info', 'brands.id','=','brand_info.brand_id')
                    ->select('users.name', 'users.email', 'brand_info.*')
                    ->get();
            if(count($accountInfo) ==0){
                return "none";
            } else {
                $accountInfo[0]->accountType = 'brand';
            }
        } else {
            $accountInfo[0]->accountType = 'influencer';
        }
        // echo $accountInfo[0]->accountType;
        return $accountInfo;
    }

    public function checkIfInfluencer($user_id) {
        $influencer = DB::table('influencers')
                ->where('user_id', $user_id)
                ->get();
        if(count($influencer) > 0) {
            return true;
        } else {
            return false;
        }
    }
}
