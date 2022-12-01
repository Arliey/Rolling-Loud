<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    use HasFactory;

    protected $fillable = ['id_ticket', 'konser_name', 'ticket_category', 'date', 'price', 'stock'];

    public function transaction()
    {
        return $this->hasMany(Transaction::class);
    }
}
