<?php 

namespace App;

use Illuminate\Database\Eloquent\Model;

trait Favoritable {

    protected static function bootFavoritable()
    {
        static::deleting(function($model) {
            $model->favorites->each->delete();
        });
    }

    public function favorites()
    {
    	return $this->morphMany(Favorite::class, 'favorited');
    }


    public function favorite()
    {
    	$user_id = auth()->id();

    	if (! $this->favorites()->where(['user_id' => $user_id])->exists())
    	{
    		return $this->favorites()->create(['user_id' => $user_id]);
    	}
    }

    public function unfavorite()
    {
        $user_id = auth()->id();

        $this->favorites()->where(['user_id' => $user_id])->get()->each(function($favorite) {
            $favorite->delete();
        });
    }

    public function isFavorited()
    {
    	return $this->favorites->where('user_id', auth()->id())->count();
    }

    public function getFavoritesCountAttribute() 
    {
        return $this->favorites->count();
    }

    public function getIsFavoritedAttribute() 
    {
        return $this->isFavorited();
    }
}