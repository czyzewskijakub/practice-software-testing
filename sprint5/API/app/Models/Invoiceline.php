<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @OA\Schema(
 *     schema="InvoiceLineResponse",
 *     type="object",
 *     title="InvoiceLineResponse",
 *     properties={
 *         @OA\Property(property="id", type="string", example=1),
 *         @OA\Property(property="unit_price", type="number"),
 *         @OA\Property(property="quantity", type="integer"),
 *         @OA\Property(property="product", ref="#/components/schemas/ProductResponse")
 *     }
 * )
 */
class Invoiceline extends BaseModel
{
    use HasUlids;
    protected $table = 'invoice_items';
    protected $fillable = ['invoice_id', 'product_id', 'unit_price', 'quantity'];

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
