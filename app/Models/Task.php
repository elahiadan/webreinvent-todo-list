<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    const TABLE = 'tasks';
    const ID = 'id';
    const TITLE = 'title';
    const IS_COMPLETED = 'is_completed';

    protected $fillable  = [self::TITLE];
}
