<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Business extends Model
{
    use HasFactory;
    protected $connection = 'mysql';
    protected $primaryKey = 'business_id';
    protected $table = 'business';

    protected $fillable = [
        'business_name',
        'business_address',
        'business_owner_name',
        'bplo_account_number',
        'business_category',
        'business_classification',
        'business_description',
        'learner_id', // Assuming you have a foreign key column
    ];

    public function learner()
    {
        return $this->belongsTo(Learner::class);
    }
}
