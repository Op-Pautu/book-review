<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;
    protected $fillable = ['review', 'rating'];
    public function book() {
        return $this->belongsTo(Book::class);
    }
    
    public function scopeHighestRated(Builder $query, $from = null, $to = null) {
        return $query->withAvg([
            'reviews' => fn(Builder $q) => $this->dateRangeFilter($q, $from, $to)
            
        ], 'rating')->orderBy('reviews_count', 'desc');
    }

    public static function booted() {
        static::updated(fn (Review $review) => cache()->forget('book:' . $review->book_id));
        static::deleted(fn (Review $review) => cache()->forget('book:' . $review->book_id));
    }
   
}
