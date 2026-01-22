<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Assignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'teacher_id',
        'type',
        'title',
        'description',
        'question',
        'estimated_date',
        'choices',
        'short_answer',
        'choice_answer',
        'yes_no_answer',
        'multiple_choices_answer',
        'multiple_response_answer',
        'top_student',
    ];

    protected $casts = [
        'choices' => 'array',
        'multiple_choices_answer' => 'array',
        'multiple_response_answer' => 'array',
        'yes_no_answer' => 'boolean',
        'estimated_date' => 'date',
    ];

    /**
     * Teacher who created the assignment
     */
    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    /**
     * Submissions made by students
     */
    public function submissions()
    {
        return $this->hasMany(Submission::class);
    }

    /**
     * Top student (optional)
     */
    public function topStudent()
    {
        return $this->belongsTo(User::class, 'top_student');
    }
}
