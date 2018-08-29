<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;

class Post extends Model
{
    //table name
    protected $table = 'posts';

    //Set the primary id
    public $primaryKey = 'id';

    public $timestamps = true;

    public function user()
    {
    	return $this->belongsTo('App\User');
    }

}
