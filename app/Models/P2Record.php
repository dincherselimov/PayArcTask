<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class P2Record extends Model
{
    use HasFactory;
    
    protected $table = 'p2_records';

    // Define a dynamic fillable attribute
    protected $fillable = [];

    public function setFillableAttributes(array $attributes)
    {
        $this->fillable = $attributes;
    }
}
