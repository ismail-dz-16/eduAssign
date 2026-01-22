<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'title',
        'description',
        'question',
        'short_answer',
        'choice_answer',
        'yes_no_answer',
    ];

    protected $casts = [
        'choice_answer' => 'array',
    ];
}
