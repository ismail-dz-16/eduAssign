<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Submission extends Model
{
    protected $fillable = [
        'assignment_id',
        'student_id',
        'short_answer',
        'choice_answer', // Corrected spelling
        'yes_no_answer',
        'multiple_choices_answer',
        'multiple_response_answer',
    ];

    protected $casts = [
        'multiple_choices_answer'   => 'array',
        'multiple_response_answer'  => 'array',
        'yes_no_answer'             => 'boolean',
    ];

    public function assignment() { return $this->belongsTo(Assignment::class); }
    public function student() { return $this->belongsTo(User::class, 'student_id'); }
}