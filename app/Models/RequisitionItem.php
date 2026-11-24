<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RequisitionItem extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'requisition_items';

    protected $fillable = [
        'requisition_id',
        'product_name',
        'quantity',
        'quantity_used',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'quantity_used' => 'integer',
    ];

    /**
     * Requisición matriz a la que pertenece el artículo.
     */
    public function requisicion(): BelongsTo
    {
        return $this->belongsTo(Requisition::class, 'requisition_id');
    }
}
