<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Recipe;
use App\Models\Feedback;
use App\Models\Ingredient;
use App\Models\RecipeImage;
use Livewire\WithPagination;
use DB;

class Pagination extends Component
{

    public $search;

    public $ingredients = [];
    
    use WithPagination;


    protected $paginationTheme = 'bootstrap';

    protected $queryString = ['search'];
   
    
    public function render()
    {
        if($this->search)
        {
            $dish = Recipe::where('recipes.recipe_name', 'LIKE', "%{$this->search}%")->where('is_approved', 1)
                          ->paginate(12);
        } 
        else 
        {
            $dish = Recipe::paginate(12)->where('is_approved', 1);
        }

        foreach ($dish->items() as $key => $value) {
    
            $dish[$key]->ingredient_count = Ingredient::where('recipe_id', $value->id)->count(); 

            $dish[$key]->average_rating = Feedback::where('recipe_id', $value->id)->avg('rating');

            $dish[$key]->image_file = RecipeImage::where('recipe_id', $value->id)->value('recipe_image');
        }
        return view('livewire.pagination', [
            'dish' => $dish,
            'query' => $this->search,
            'message' => (count($dish) == 0) ? true : false
        ]);

    }

    public function updated($property)
    {

        if($property == 'search')
        {
            $this->resetPage();
        }
    }

}