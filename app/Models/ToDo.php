<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ToDo extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'todos';
    protected $fillable = [
        'name',
        'description',
        'creation_date',
        'creation_time',
        'done',
        'category_id',
        'user_id'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function users()
    {
        return $this->belongsTo(User::class);
    }
    public function sharedWithUsers()
    {
        return $this->belongsToMany(User::class, 'shared_todos', 'todo_id', 'user_id');
    }

}
