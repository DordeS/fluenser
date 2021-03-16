<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Influencers extends Model
{
    use HasFactory;

    protected $table = "influencers";

    protected $fillable = [
        'user_id',
    ];

    public function findInfluencers($category, $country, $name, $keyword) {

        $influencers = DB::table('users')
                ->join('influencers', 'influencers.user_id', '=', 'users.id')
                ->join('influencers_info', 'influencers.id', '=', 'influencers_info.influencer_id')
                ->join('category_influencer', 'category_influencer.influencer_id', '=', 'influencers_info.influencer_id')
                ->join('categories', 'categories.id', '=', 'category_influencer.category_id');
        
        if($category != 'Category')
            $influencers = $influencers->where('categories.category_name', '=', $category);

        if($country != "Location")
            $influencers = $influencers->where('influencers_info.country', '=', $country);

        if($name != '')
            $influencers = $influencers->where('users.name', 'LIKE', '%'.$name.'%');
        
        // if($keyword != '')
        //     $influencers = $influencers->where('users.email', '=')

        $influencers = $influencers->select([
            'users.id',
            'users.name',
            'influencers_info.influencer_id',
            'influencers_info.country',
            'influencers_info.state',
            'influencers_info.follows',
            'influencers_info.followings',
            'influencers_info.posts',
            'influencers_info.avatar',
            'influencers_info.back_img',
            'categories.category_name',
        ])->limit(1)->get();

        return $influencers;
    }
}
