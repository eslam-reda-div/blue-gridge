<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SupplyRequestLog extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'supply_request_id',
        'supplier_id',
        'action',
        'details',
    ];

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
        ];
    }

    /**
     * Get the supply request.
     */
    public function supplyRequest(): BelongsTo
    {
        return $this->belongsTo(SupplyRequest::class);
    }

    /**
     * Get the supplier (if applicable).
     */
    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    /**
     * Human-readable label for the action.
     *
     * @return array{label: string, color: string}
     */
    public function actionDisplay(): array
    {
        return match ($this->action) {
            'request_created' => ['label' => 'Request Created', 'color' => 'info'],
            'sent_to_supplier' => ['label' => 'Sent to Supplier', 'color' => 'info'],
            'supplier_accepted' => ['label' => 'Supplier Accepted', 'color' => 'success'],
            'supplier_rejected' => ['label' => 'Supplier Rejected', 'color' => 'danger'],
            'supplier_dismissed' => ['label' => 'Supplier Dismissed', 'color' => 'gray'],
            'request_fulfilled' => ['label' => 'Request Fulfilled', 'color' => 'success'],
            'all_rejected' => ['label' => 'All Suppliers Rejected', 'color' => 'danger'],
            default => ['label' => $this->action, 'color' => 'gray'],
        };
    }
}
