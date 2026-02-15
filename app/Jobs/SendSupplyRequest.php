<?php

namespace App\Jobs;

use App\Models\Factory;
use App\Models\MaterialSubCategory;
use App\Models\SupplierSubMaterial;
use App\Models\SupplyRequest;
use App\Models\SupplyRequestLog;
use App\Models\SupplyRequestSupplier;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendSupplyRequest implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public int $factoryId,
        public int $materialSubCategoryId,
        public float $quantityNeeded,
        public string $triggeredBy = 'auto',
    ) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Prevent duplicate pending requests for the same factory + material
        $existingPending = SupplyRequest::query()
            ->where('factory_id', $this->factoryId)
            ->where('material_sub_category_id', $this->materialSubCategoryId)
            ->where('status', 'pending')
            ->exists();

        if ($existingPending) {
            return;
        }

        $factory = Factory::findOrFail($this->factoryId);
        $materialSubCategory = MaterialSubCategory::with('material')->findOrFail($this->materialSubCategoryId);

        // Get all connected suppliers who carry this sub-material
        $connectedSupplierIds = $factory->suppliers()->pluck('suppliers.id');

        $eligibleSuppliers = SupplierSubMaterial::withoutGlobalScopes()
            ->whereIn('supplier_id', $connectedSupplierIds)
            ->where('material_sub_category_id', $this->materialSubCategoryId)
            ->with('supplier')
            ->get();

        if ($eligibleSuppliers->isEmpty()) {
            return;
        }

        // Create the supply request
        $supplyRequest = SupplyRequest::create([
            'factory_id' => $this->factoryId,
            'material_sub_category_id' => $this->materialSubCategoryId,
            'quantity_needed' => $this->quantityNeeded,
            'status' => 'pending',
            'triggered_by' => $this->triggeredBy,
        ]);

        // Log: request created
        SupplyRequestLog::create([
            'supply_request_id' => $supplyRequest->id,
            'action' => 'request_created',
            'details' => "Supply request created ({$this->triggeredBy}) for {$materialSubCategory->material->name} — {$materialSubCategory->name}. Quantity needed: {$this->quantityNeeded}",
        ]);

        // Send to each eligible supplier
        foreach ($eligibleSuppliers as $supplierStock) {
            SupplyRequestSupplier::create([
                'supply_request_id' => $supplyRequest->id,
                'supplier_id' => $supplierStock->supplier_id,
                'status' => 'pending',
            ]);

            // Log: sent to supplier
            SupplyRequestLog::create([
                'supply_request_id' => $supplyRequest->id,
                'supplier_id' => $supplierStock->supplier_id,
                'action' => 'sent_to_supplier',
                'details' => "Request sent to supplier: {$supplierStock->supplier->name} (Stock available: {$supplierStock->quantity})",
            ]);
        }
    }
}
