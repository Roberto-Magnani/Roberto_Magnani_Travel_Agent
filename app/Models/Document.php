<?php

namespace App\Models;

use App\Models\Embedding;
use Smalot\PdfParser\Parser;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Document extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 
        'path', 
        'mime', 
        'size'
    ];

    public function getHumanReadableSizeAttribute(): string
    {
        $i = floor(log($this->size) / log(1024));
        return number_format(($this->size / pow(1024, $i)), 2) . ' ' . ['B', 'kB', 'MB', 'GB', 'TB'][$i] ;
    }
}
