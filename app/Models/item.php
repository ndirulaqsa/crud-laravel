<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    // Daftarkan kolom yang boleh diisi lewat Postman
    protected $fillable = ['name', 'price', 'category_id'];

    // Tambahkan ini juga untuk relasi (Soal No. 3)
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}