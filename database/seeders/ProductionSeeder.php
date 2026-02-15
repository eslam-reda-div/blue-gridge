<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\Factory;
use App\Models\FactoryEmployee;
use App\Models\FactorySubMaterial;
use App\Models\Material;
use App\Models\MaterialSubCategory;
use App\Models\Seller;
use App\Models\SellingOffer;
use App\Models\SellingOfferTarget;
use App\Models\Supplier;
use App\Models\SupplierEmployee;
use App\Models\SupplierSubMaterial;
use App\Models\SupplierSubMaterialTransaction;
use App\Models\SupplyRequest;
use App\Models\SupplyRequestLog;
use App\Models\SupplyRequestSupplier;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ProductionSeeder extends Seeder
{
    private string $password;

    public function run(): void
    {
        $this->password = Hash::make('password');

        $this->command->info('🔧 Seeding Admins...');
        $this->seedAdmins();

        $this->command->info('🏭 Seeding Materials & Sub-Categories...');
        $materials = $this->seedMaterials();

        $this->command->info('🏭 Seeding Factories & Employees...');
        $factories = $this->seedFactories();

        $this->command->info('📦 Seeding Suppliers & Employees...');
        $suppliers = $this->seedSuppliers();

        $this->command->info('🔗 Connecting Factories ↔ Suppliers...');
        $this->connectFactoriesAndSuppliers($factories, $suppliers);

        $this->command->info('📊 Seeding Supplier Materials & Stock...');
        $this->seedSupplierMaterials($suppliers, $materials);

        $this->command->info('📊 Seeding Factory Materials & Stock...');
        $this->seedFactoryMaterials($factories, $materials);

        $this->command->info('📋 Seeding Supply Requests...');
        $this->seedSupplyRequests($factories, $suppliers, $materials);

        $this->command->info('🛒 Seeding Sellers...');
        $sellers = $this->seedSellers();

        $this->command->info('📣 Seeding Selling Offers...');
        $this->seedSellingOffers($sellers, $suppliers, $materials);

        $this->command->newLine();
        $this->command->info('✅ Production seed complete!');
        $this->printLoginCredentials();
    }

    private function seedAdmins(): void
    {
        $admins = [
            ['name' => 'Eslam Reda', 'email' => 'admin@bluebridge.com'],
            ['name' => 'Mohamed Hassan', 'email' => 'mohamed@bluebridge.com'],
            ['name' => 'Sara Ahmed', 'email' => 'sara@bluebridge.com'],
        ];

        foreach ($admins as $admin) {
            Admin::create([...$admin, 'password' => $this->password]);
        }
    }

    /**
     * @return array<int, array{material: Material, subs: \Illuminate\Support\Collection<int, MaterialSubCategory>}>
     */
    private function seedMaterials(): array
    {
        $materialsData = [
            'Steel' => ['Hot-Rolled Steel', 'Cold-Rolled Steel', 'Galvanized Steel', 'Stainless Steel', 'Steel Rebar'],
            'Aluminum' => ['Aluminum Sheet', 'Aluminum Foil', 'Aluminum Extrusion', 'Aluminum Wire'],
            'Copper' => ['Copper Wire', 'Copper Pipe', 'Copper Sheet', 'Copper Rod'],
            'Wood' => ['Pine Lumber', 'Oak Planks', 'Plywood Sheets', 'MDF Board', 'Particle Board'],
            'Plastic' => ['PVC Pellets', 'Polyethylene Film', 'Polypropylene Granules', 'ABS Sheets', 'Nylon Rods'],
            'Glass' => ['Float Glass', 'Tempered Glass', 'Laminated Glass'],
            'Rubber' => ['Natural Rubber', 'Synthetic Rubber', 'Silicone Rubber', 'Neoprene Sheets'],
            'Concrete' => ['Portland Cement', 'Ready-Mix Concrete', 'Concrete Blocks', 'Rebar Mesh'],
            'Textiles' => ['Cotton Fabric', 'Polyester Fabric', 'Nylon Thread', 'Canvas Rolls'],
            'Chemicals' => ['Industrial Solvents', 'Adhesive Compounds', 'Paint Base', 'Epoxy Resin', 'Lubricant Oil'],
        ];

        $result = [];

        foreach ($materialsData as $materialName => $subCategories) {
            $material = Material::create(['name' => $materialName]);

            $subs = collect();
            foreach ($subCategories as $subName) {
                $subs->push(MaterialSubCategory::create([
                    'material_id' => $material->id,
                    'name' => $subName,
                ]));
            }

            $result[] = ['material' => $material, 'subs' => $subs];
        }

        return $result;
    }

    /**
     * @return \Illuminate\Support\Collection<int, Factory>
     */
    private function seedFactories(): \Illuminate\Support\Collection
    {
        $factoriesData = [
            ['name' => 'BlueBridge Manufacturing', 'location' => 'Alexandria, Egypt', 'employees' => [
                ['name' => 'Ahmed Mostafa', 'email' => 'ahmed@bluebridge-mfg.com'],
                ['name' => 'Fatma Ali', 'email' => 'fatma@bluebridge-mfg.com'],
                ['name' => 'Youssef Kamal', 'email' => 'youssef@bluebridge-mfg.com'],
            ]],
            ['name' => 'Delta Steel Works', 'location' => '10th of Ramadan City, Egypt', 'employees' => [
                ['name' => 'Hassan Ibrahim', 'email' => 'hassan@deltasteel.com'],
                ['name' => 'Mona Saeed', 'email' => 'mona@deltasteel.com'],
            ]],
            ['name' => 'Nile Industrial Group', 'location' => '6th of October City, Egypt', 'employees' => [
                ['name' => 'Khaled Nasser', 'email' => 'khaled@nileindustrial.com'],
                ['name' => 'Dina Rashid', 'email' => 'dina@nileindustrial.com'],
                ['name' => 'Omar Tarek', 'email' => 'omar@nileindustrial.com'],
            ]],
            ['name' => 'Cairo Plastics Co.', 'location' => 'Helwan, Cairo, Egypt', 'employees' => [
                ['name' => 'Amr Sayed', 'email' => 'amr@cairoplastics.com'],
                ['name' => 'Nada Fouad', 'email' => 'nada@cairoplastics.com'],
            ]],
            ['name' => 'Suez Glass Factory', 'location' => 'Suez, Egypt', 'employees' => [
                ['name' => 'Tamer Adel', 'email' => 'tamer@suezglass.com'],
                ['name' => 'Rania Magdy', 'email' => 'rania@suezglass.com'],
            ]],
        ];

        $factories = collect();

        foreach ($factoriesData as $factoryData) {
            $factory = Factory::create([
                'name' => $factoryData['name'],
                'location' => $factoryData['location'],
            ]);

            foreach ($factoryData['employees'] as $employee) {
                FactoryEmployee::create([
                    ...$employee,
                    'factory_id' => $factory->id,
                    'password' => $this->password,
                ]);
            }

            $factories->push($factory);
        }

        return $factories;
    }

    /**
     * @return \Illuminate\Support\Collection<int, Supplier>
     */
    private function seedSuppliers(): \Illuminate\Support\Collection
    {
        $suppliersData = [
            ['name' => 'Egyptian Steel Supply', 'location' => 'Port Said, Egypt', 'employees' => [
                ['name' => 'Mahmoud Fathy', 'email' => 'mahmoud@egysteelsupply.com'],
                ['name' => 'Laila Hamdy', 'email' => 'laila@egysteelsupply.com'],
            ]],
            ['name' => 'Al-Sharq Raw Materials', 'location' => 'Ismailia, Egypt', 'employees' => [
                ['name' => 'Wael Sabry', 'email' => 'wael@alsharq.com'],
                ['name' => 'Heba Mohamed', 'email' => 'heba@alsharq.com'],
                ['name' => 'Tarek Zaki', 'email' => 'tarek@alsharq.com'],
            ]],
            ['name' => 'Delta Trading Corp', 'location' => 'Mansoura, Egypt', 'employees' => [
                ['name' => 'Samir Nabil', 'email' => 'samir@deltatrading.com'],
                ['name' => 'Noha Said', 'email' => 'noha@deltatrading.com'],
            ]],
            ['name' => 'Upper Egypt Materials', 'location' => 'Assiut, Egypt', 'employees' => [
                ['name' => 'Ibrahim Gamal', 'email' => 'ibrahim@uppermaterials.com'],
                ['name' => 'Asmaa Refaat', 'email' => 'asmaa@uppermaterials.com'],
            ]],
            ['name' => 'Red Sea Logistics', 'location' => 'Hurghada, Egypt', 'employees' => [
                ['name' => 'Karim Essam', 'email' => 'karim@redsealogistics.com'],
            ]],
            ['name' => 'Cairo General Supply', 'location' => 'Nasr City, Cairo, Egypt', 'employees' => [
                ['name' => 'Sherif Hassan', 'email' => 'sherif@cairogeneral.com'],
                ['name' => 'Yasmin Alaa', 'email' => 'yasmin@cairogeneral.com'],
                ['name' => 'Mostafa Helmy', 'email' => 'mostafa@cairogeneral.com'],
            ]],
            ['name' => 'Alexandria Port Supplies', 'location' => 'Alexandria, Egypt', 'employees' => [
                ['name' => 'Adel Mansour', 'email' => 'adel@alexsupplies.com'],
                ['name' => 'Samia Youssef', 'email' => 'samia@alexsupplies.com'],
            ]],
            ['name' => 'Sinai Minerals Co.', 'location' => 'El-Arish, Egypt', 'employees' => [
                ['name' => 'Hesham Lotfy', 'email' => 'hesham@sinaiminerals.com'],
            ]],
        ];

        $suppliers = collect();

        foreach ($suppliersData as $supplierData) {
            $supplier = Supplier::create([
                'name' => $supplierData['name'],
                'location' => $supplierData['location'],
            ]);

            foreach ($supplierData['employees'] as $employee) {
                SupplierEmployee::create([
                    ...$employee,
                    'supplier_id' => $supplier->id,
                    'password' => $this->password,
                ]);
            }

            $suppliers->push($supplier);
        }

        return $suppliers;
    }

    /**
     * @param  \Illuminate\Support\Collection<int, Factory>  $factories
     * @param  \Illuminate\Support\Collection<int, Supplier>  $suppliers
     */
    private function connectFactoriesAndSuppliers($factories, $suppliers): void
    {
        // Each factory connects to 3-6 suppliers
        foreach ($factories as $factory) {
            $connectedSuppliers = $suppliers->random(rand(3, min(6, $suppliers->count())));
            $factory->suppliers()->attach($connectedSuppliers->pluck('id'));
        }
    }

    /**
     * @param  \Illuminate\Support\Collection<int, Supplier>  $suppliers
     */
    private function seedSupplierMaterials($suppliers, array $materials): void
    {
        // Each supplier handles 4-12 random sub-categories
        foreach ($suppliers as $supplier) {
            $allSubs = collect($materials)->flatMap(fn ($m) => $m['subs']);
            $selectedSubs = $allSubs->random(rand(4, min(12, $allSubs->count())));

            foreach ($selectedSubs as $sub) {
                // Create initial stock via transaction (triggers booted() auto-stock)
                $initialQty = fake()->randomFloat(2, 50, 2000);

                SupplierSubMaterialTransaction::withoutGlobalScopes()->create([
                    'supplier_id' => $supplier->id,
                    'material_sub_category_id' => $sub->id,
                    'type' => 'insert',
                    'quantity' => $initialQty,
                    'notes' => 'Initial stock load',
                ]);

                // Add some usage transactions for realism
                $usageCount = rand(2, 8);
                for ($i = 0; $i < $usageCount; $i++) {
                    $type = fake()->randomElement(['insert', 'insert', 'use']); // More inserts than usage
                    $qty = fake()->randomFloat(2, 5, min(200, $initialQty * 0.3));

                    SupplierSubMaterialTransaction::withoutGlobalScopes()->create([
                        'supplier_id' => $supplier->id,
                        'material_sub_category_id' => $sub->id,
                        'type' => $type,
                        'quantity' => $qty,
                        'notes' => $type === 'insert'
                            ? fake()->randomElement(['Shipment received', 'Bulk purchase', 'Restocked from warehouse', 'Monthly delivery'])
                            : fake()->randomElement(['Delivered to factory', 'Sold to client', 'Used for packaging', 'Quality sample']),
                    ]);
                }
            }
        }
    }

    /**
     * @param  \Illuminate\Support\Collection<int, Factory>  $factories
     */
    private function seedFactoryMaterials($factories, array $materials): void
    {
        // Each factory uses 5-15 random sub-categories
        foreach ($factories as $factory) {
            $allSubs = collect($materials)->flatMap(fn ($m) => $m['subs']);
            $selectedSubs = $allSubs->random(rand(5, min(15, $allSubs->count())));

            foreach ($selectedSubs as $sub) {
                // Set safe amount first by directly creating the stock record
                $safeAmount = fake()->randomFloat(2, 20, 200);
                $initialQty = fake()->randomFloat(2, 100, 1500);

                // Create the stock record with safe_amount (bypass transaction trigger)
                $stock = FactorySubMaterial::withoutGlobalScopes()->create([
                    'factory_id' => $factory->id,
                    'material_sub_category_id' => $sub->id,
                    'quantity' => $initialQty,
                    'safe_amount' => $safeAmount,
                ]);

                // Add transaction history (without triggering stock recalc - use DB insert)
                $txnData = [];
                $txnData[] = [
                    'factory_id' => $factory->id,
                    'material_sub_category_id' => $sub->id,
                    'type' => 'insert',
                    'quantity' => $initialQty,
                    'notes' => 'Initial inventory load',
                    'created_at' => now()->subDays(rand(20, 60)),
                    'updated_at' => now()->subDays(rand(20, 60)),
                ];

                // Simulate production history
                $txnCount = rand(5, 15);
                for ($i = 0; $i < $txnCount; $i++) {
                    $type = fake()->randomElement(['insert', 'use', 'use']); // More usage in factory
                    $qty = fake()->randomFloat(2, 5, 100);
                    $daysAgo = rand(1, 20);

                    $txnData[] = [
                        'factory_id' => $factory->id,
                        'material_sub_category_id' => $sub->id,
                        'type' => $type,
                        'quantity' => $qty,
                        'notes' => $type === 'insert'
                            ? fake()->randomElement(['Supplier delivery', 'Emergency restock', 'Monthly supply', 'Warehouse transfer'])
                            : fake()->randomElement(['Production batch #'.rand(100, 999), 'Assembly line A', 'Assembly line B', 'Quality testing', 'Product finishing']),
                        'created_at' => now()->subDays($daysAgo),
                        'updated_at' => now()->subDays($daysAgo),
                    ];
                }

                DB::table('factory_sub_material_transactions')->insert($txnData);
            }
        }
    }

    /**
     * @param  \Illuminate\Support\Collection<int, Factory>  $factories
     * @param  \Illuminate\Support\Collection<int, Supplier>  $suppliers
     */
    private function seedSupplyRequests($factories, $suppliers, array $materials): void
    {
        $allSubs = collect($materials)->flatMap(fn ($m) => $m['subs']);

        foreach ($factories as $factory) {
            $connectedSupplierIds = $factory->suppliers()->pluck('suppliers.id');
            if ($connectedSupplierIds->isEmpty()) {
                continue;
            }

            // 3-6 supply requests per factory
            $requestCount = rand(3, 6);
            for ($r = 0; $r < $requestCount; $r++) {
                $sub = $allSubs->random();
                $status = fake()->randomElement(['pending', 'pending', 'accepted', 'rejected']);
                $acceptedSupplierId = null;

                $request = SupplyRequest::create([
                    'factory_id' => $factory->id,
                    'material_sub_category_id' => $sub->id,
                    'quantity_needed' => fake()->randomFloat(2, 20, 500),
                    'status' => $status,
                    'triggered_by' => fake()->randomElement(['auto', 'auto', 'manual']),
                    'accepted_by_supplier_id' => null,
                ]);

                // Log: request created
                SupplyRequestLog::create([
                    'supply_request_id' => $request->id,
                    'supplier_id' => null,
                    'action' => 'request_created',
                    'details' => "Supply request created for {$sub->name}, qty: {$request->quantity_needed}",
                ]);

                // Create supplier entries
                $selectedSuppliers = $connectedSupplierIds->random(min(rand(2, 4), $connectedSupplierIds->count()));

                foreach ($selectedSuppliers as $supplierId) {
                    $supplierStatus = 'pending';
                    $rejectionReason = null;
                    $respondedAt = null;

                    if ($status === 'accepted') {
                        if ($acceptedSupplierId === null && fake()->boolean(50)) {
                            $supplierStatus = 'accepted';
                            $acceptedSupplierId = $supplierId;
                            $respondedAt = now()->subDays(rand(1, 5));
                        } else {
                            $supplierStatus = $acceptedSupplierId ? 'dismissed' : 'pending';
                            $respondedAt = $acceptedSupplierId ? now()->subDays(rand(1, 5)) : null;
                        }
                    } elseif ($status === 'rejected') {
                        $supplierStatus = 'rejected';
                        $rejectionReason = fake()->randomElement([
                            'Out of stock for this material',
                            'Cannot fulfill the requested quantity',
                            'Delivery schedule conflict',
                            'Price negotiation needed',
                            'Material quality does not match requirements',
                        ]);
                        $respondedAt = now()->subDays(rand(1, 5));
                    }

                    SupplyRequestSupplier::create([
                        'supply_request_id' => $request->id,
                        'supplier_id' => $supplierId,
                        'status' => $supplierStatus,
                        'rejection_reason' => $rejectionReason,
                        'responded_at' => $respondedAt,
                    ]);

                    // Log: sent to supplier
                    $supplierName = $suppliers->firstWhere('id', $supplierId)?->name ?? "Supplier #{$supplierId}";
                    SupplyRequestLog::create([
                        'supply_request_id' => $request->id,
                        'supplier_id' => $supplierId,
                        'action' => 'sent_to_supplier',
                        'details' => "Request sent to {$supplierName}",
                    ]);

                    // Log actions based on status
                    if ($supplierStatus === 'accepted') {
                        SupplyRequestLog::create([
                            'supply_request_id' => $request->id,
                            'supplier_id' => $supplierId,
                            'action' => 'supplier_accepted',
                            'details' => "{$supplierName} accepted the supply request",
                        ]);
                    } elseif ($supplierStatus === 'rejected') {
                        SupplyRequestLog::create([
                            'supply_request_id' => $request->id,
                            'supplier_id' => $supplierId,
                            'action' => 'supplier_rejected',
                            'details' => "{$supplierName} rejected: {$rejectionReason}",
                        ]);
                    } elseif ($supplierStatus === 'dismissed') {
                        SupplyRequestLog::create([
                            'supply_request_id' => $request->id,
                            'supplier_id' => $supplierId,
                            'action' => 'supplier_dismissed',
                            'details' => "{$supplierName} dismissed — another supplier accepted",
                        ]);
                    }
                }

                // If accepted, set the accepted supplier
                if ($status === 'accepted' && $acceptedSupplierId) {
                    $request->update(['accepted_by_supplier_id' => $acceptedSupplierId]);

                    SupplyRequestLog::create([
                        'supply_request_id' => $request->id,
                        'supplier_id' => $acceptedSupplierId,
                        'action' => 'request_fulfilled',
                        'details' => 'Supply request has been fulfilled',
                    ]);
                } elseif ($status === 'accepted' && ! $acceptedSupplierId) {
                    // Force one to accept
                    $firstSupplierEntry = SupplyRequestSupplier::where('supply_request_id', $request->id)->first();
                    if ($firstSupplierEntry) {
                        $firstSupplierEntry->update(['status' => 'accepted', 'responded_at' => now()->subDays(2)]);
                        $request->update(['accepted_by_supplier_id' => $firstSupplierEntry->supplier_id]);

                        SupplyRequestLog::create([
                            'supply_request_id' => $request->id,
                            'supplier_id' => $firstSupplierEntry->supplier_id,
                            'action' => 'supplier_accepted',
                            'details' => 'Supplier accepted the request',
                        ]);
                        SupplyRequestLog::create([
                            'supply_request_id' => $request->id,
                            'supplier_id' => $firstSupplierEntry->supplier_id,
                            'action' => 'request_fulfilled',
                            'details' => 'Supply request has been fulfilled',
                        ]);
                    }
                } elseif ($status === 'rejected') {
                    SupplyRequestLog::create([
                        'supply_request_id' => $request->id,
                        'supplier_id' => null,
                        'action' => 'all_rejected',
                        'details' => 'All suppliers rejected this request',
                    ]);
                }
            }
        }
    }

    /**
     * @return \Illuminate\Support\Collection<int, Seller>
     */
    private function seedSellers(): \Illuminate\Support\Collection
    {
        $sellersData = [
            ['name' => 'Ali Recycling Co.', 'email' => 'ali@recycling.com'],
            ['name' => 'Nile Scrap Traders', 'email' => 'info@nilescrap.com'],
            ['name' => 'Green Materials Egypt', 'email' => 'sales@greenmaterials.com'],
            ['name' => 'Cairo Surplus Depot', 'email' => 'depot@cairosurplus.com'],
            ['name' => 'Delta Metal Salvage', 'email' => 'contact@deltametal.com'],
            ['name' => 'Pharaoh Waste Solutions', 'email' => 'deals@pharaohwaste.com'],
            ['name' => 'Red Sea Scrap Yard', 'email' => 'yard@redseasellscrap.com'],
            ['name' => 'Giza Industrial Surplus', 'email' => 'surplus@gizaindustrial.com'],
        ];

        $sellers = collect();

        foreach ($sellersData as $sellerData) {
            $sellers->push(Seller::create([
                ...$sellerData,
                'password' => $this->password,
            ]));
        }

        return $sellers;
    }

    /**
     * @param  \Illuminate\Support\Collection<int, Seller>  $sellers
     * @param  \Illuminate\Support\Collection<int, Supplier>  $suppliers
     */
    private function seedSellingOffers($sellers, $suppliers, array $materials): void
    {
        $allSubs = collect($materials)->flatMap(fn ($m) => $m['subs']);

        foreach ($sellers as $seller) {
            // Each seller creates 3-8 offers
            $offerCount = rand(3, 8);

            for ($o = 0; $o < $offerCount; $o++) {
                $sub = $allSubs->random();
                $type = fake()->randomElement(['broadcast', 'broadcast', 'targeted']);
                $status = fake()->randomElement(['open', 'open', 'open', 'accepted', 'closed']);

                $acceptedBySupplierId = null;
                $acceptedAt = null;

                if ($status === 'accepted') {
                    $acceptedBySupplierId = $suppliers->random()->id;
                    $acceptedAt = now()->subDays(rand(1, 10));
                }

                $offer = SellingOffer::create([
                    'seller_id' => $seller->id,
                    'material_sub_category_id' => $sub->id,
                    'quantity' => fake()->randomFloat(2, 10, 800),
                    'price_per_unit' => fake()->boolean(70) ? fake()->randomFloat(2, 5, 500) : null,
                    'notes' => fake()->boolean(60) ? fake()->randomElement([
                        'Material is in good condition, collected from factory surplus. Pickup available at our warehouse in Cairo.',
                        'Scrap metal from demolition site. Bulk discount available for orders over 100 units.',
                        'Clean and sorted material, ready for immediate use. Delivery can be arranged.',
                        'Excess inventory from our latest project. Quality certified, documentation available.',
                        'Recycled material, suitable for manufacturing. Inspection welcome at our facility.',
                        'Premium grade surplus — never opened, original packaging. Negotiable for large quantities.',
                        'Mixed lot from warehouse clearance. Some minor surface oxidation but structurally sound.',
                        null,
                    ]) : null,
                    'type' => $type,
                    'status' => $status,
                    'accepted_by_supplier_id' => $acceptedBySupplierId,
                    'accepted_at' => $acceptedAt,
                ]);

                // Create target entries for targeted offers
                if ($type === 'targeted') {
                    // Find suppliers who have this material
                    $eligibleSupplierIds = SupplierSubMaterial::withoutGlobalScopes()
                        ->where('material_sub_category_id', $sub->id)
                        ->pluck('supplier_id')
                        ->unique();

                    if ($eligibleSupplierIds->isEmpty()) {
                        // Fallback: pick random suppliers
                        $eligibleSupplierIds = $suppliers->random(rand(1, 3))->pluck('id');
                    }

                    $targetCount = min(rand(1, 3), $eligibleSupplierIds->count());
                    $targetIds = $eligibleSupplierIds->random($targetCount);

                    foreach ($targetIds as $targetSupplierId) {
                        SellingOfferTarget::create([
                            'selling_offer_id' => $offer->id,
                            'supplier_id' => $targetSupplierId,
                        ]);
                    }

                    // If accepted, make sure the accepted supplier is in targets
                    if ($acceptedBySupplierId && ! $targetIds->contains($acceptedBySupplierId)) {
                        SellingOfferTarget::create([
                            'selling_offer_id' => $offer->id,
                            'supplier_id' => $acceptedBySupplierId,
                        ]);
                    }
                }
            }
        }
    }

    private function printLoginCredentials(): void
    {
        $this->command->newLine();
        $this->command->info('╔══════════════════════════════════════════════════════════╗');
        $this->command->info('║              LOGIN CREDENTIALS (password: password)      ║');
        $this->command->info('╠══════════════════════════════════════════════════════════╣');
        $this->command->info('║ ADMIN     → admin@bluebridge.com                        ║');
        $this->command->info('║ FACTORY   → ahmed@bluebridge-mfg.com                    ║');
        $this->command->info('║ SUPPLIER  → mahmoud@egysteelsupply.com                  ║');
        $this->command->info('║ SELLER    → ali@recycling.com                           ║');
        $this->command->info('╚══════════════════════════════════════════════════════════╝');
        $this->command->newLine();
    }
}
