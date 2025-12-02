<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParentModel extends Model
{
    use HasFactory;

    protected $table = 'parents';
    protected $fillable = [
        'name',
        'amount_of_children',
        'bank_name',
        'iban',
        'bic',
    ];
}
