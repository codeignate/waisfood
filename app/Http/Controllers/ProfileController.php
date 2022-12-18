<?php

namespace App\Http\Controllers;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Post;
use App\Models\Feedback;
use App\Models\Like;
use App\Models\Recipe;

use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function index($id){

        $total_vote = [];

        $newsfeed_posts = User::join('posts', 'posts.user_id', '=', 'users.id')
                                ->where('posts.user_id', $id)
                                ->orderBy('posts.created_at', 'desc')
                                ->get()
                                ->toJson();

        $reviews_made = Feedback::where('user_id', $id)->count();

        $recipe_published = Recipe::where('author_id', $id)->where('is_approved', 1)->count();

        $recipes = Recipe::where('author_id', $id);
        
        
        //get the total count of recipe post
        $recipe_count = $recipes->where('is_approved', 0)->count();
        //get the whole value
        $upvote_count = $recipes->where('is_approved', 0)->get();
        //iterate through it to save the total vote count in each result of query above
        foreach($upvote_count as $key => $value)
        {
            $upvote_count[$key]->vote_count = Like::where('recipe_id', $value->id)->sum('like');
           
            $upvote_count[$key]->vote_count = (int) $upvote_count[$key]->vote_count;

            array_push($total_vote, $upvote_count[$key]->vote_count);

        }

        return view('user.profile', [
            'user_id' => $id,
            'reviews_count' => $reviews_made,
            'recipe_count' => $recipe_count,
            'recipe_published' => $recipe_published,
            'reviews_made' => $reviews_made,
            'total_votes' => array_sum($total_vote),

        ]);
    }

    public function edit_post($id) {

        $post_data = Post::where('id', $id)->get();

        return response()->json([
            'post_data' => $post_data
        ]);
    }
}
