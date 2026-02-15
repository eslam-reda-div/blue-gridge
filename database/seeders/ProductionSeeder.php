<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\Factory;
use App\Models\FactoryEmployee;
use App\Models\Material;
use App\Models\MaterialSubCategory;
use App\Models\Seller;
use App\Models\SellingOffer;
use App\Models\SellingOfferTarget;
use App\Models\Supplier;
use App\Models\SupplierEmployee;
use App\Models\SupplierSubMaterial;
use App\Models\SupplyRequest;
use App\Models\SupplyRequestLog;
use App\Models\SupplyRequestSupplier;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ProductionSeeder extends Seeder
{
    private string $password;

    /** @var Collection<int, MaterialSubCategory> */
    private Collection $allSubs;

    public function run(): void
    {
        $this->password = Hash::make('password');

        $this->command->info('🔧 Seeding Admins...');
        $this->seedAdmins();

        $this->command->info('🏭 Seeding Materials & Sub-Categories...');
        $this->seedMaterials();

        $this->command->info('🏭 Seeding Factories & Employees...');
        $factories = $this->seedFactories();

        $this->command->info('📦 Seeding Suppliers & Employees...');
        $suppliers = $this->seedSuppliers();

        $this->command->info('🔗 Connecting Factories ↔ Suppliers...');
        $this->connectFactoriesAndSuppliers($factories, $suppliers);

        $this->command->info('📊 Seeding Supplier Inventory & Transactions...');
        $this->seedSupplierInventory($suppliers);

        $this->command->info('📊 Seeding Factory Inventory & Transactions...');
        $this->seedFactoryInventory($factories);

        $this->command->info('📋 Seeding Supply Requests (all statuses)...');
        $this->seedSupplyRequests($factories, $suppliers);

        $this->command->info('🛒 Seeding Sellers...');
        $sellers = $this->seedSellers();

        $this->command->info('📣 Seeding Selling Offers & Targets...');
        $this->seedSellingOffers($sellers, $suppliers);

        $this->command->newLine();
        $this->command->info('✅ Production seed complete!');
        $this->printSummary();
        $this->printLoginCredentials();
    }

    // ─── Admins ────────────────────────────────────────────────

    private function seedAdmins(): void
    {
        $admins = [
            ['name' => 'Eslam Reda', 'email' => 'admin@bluebridge.com'],
            ['name' => 'Mohamed Hassan', 'email' => 'mohamed@bluebridge.com'],
            ['name' => 'Sara Ahmed', 'email' => 'sara@bluebridge.com'],
            ['name' => 'Amira Youssef', 'email' => 'amira@bluebridge.com'],
            ['name' => 'Karim Mansour', 'email' => 'karim@bluebridge.com'],
        ];

        foreach ($admins as $admin) {
            Admin::create([...$admin, 'password' => $this->password]);
        }
    }

    // ─── Materials ─────────────────────────────────────────────

    private function seedMaterials(): void
    {
        $materialsData = [
            'Steel' => ['Hot-Rolled Steel', 'Cold-Rolled Steel', 'Galvanized Steel', 'Stainless Steel', 'Steel Rebar', 'Steel Wire Rod'],
            'Aluminum' => ['Aluminum Sheet', 'Aluminum Foil', 'Aluminum Extrusion', 'Aluminum Wire', 'Aluminum Ingot'],
            'Copper' => ['Copper Wire', 'Copper Pipe', 'Copper Sheet', 'Copper Rod', 'Copper Busbar'],
            'Wood' => ['Pine Lumber', 'Oak Planks', 'Plywood Sheets', 'MDF Board', 'Particle Board', 'Birch Veneer'],
            'Plastic' => ['PVC Pellets', 'Polyethylene Film', 'Polypropylene Granules', 'ABS Sheets', 'Nylon Rods', 'Acrylic Sheets'],
            'Glass' => ['Float Glass', 'Tempered Glass', 'Laminated Glass', 'Frosted Glass'],
            'Rubber' => ['Natural Rubber', 'Synthetic Rubber', 'Silicone Rubber', 'Neoprene Sheets', 'EPDM Rubber'],
            'Concrete' => ['Portland Cement', 'Ready-Mix Concrete', 'Concrete Blocks', 'Rebar Mesh', 'Gravel Aggregate'],
            'Textiles' => ['Cotton Fabric', 'Polyester Fabric', 'Nylon Thread', 'Canvas Rolls', 'Wool Blend', 'Denim Fabric'],
            'Chemicals' => ['Industrial Solvents', 'Adhesive Compounds', 'Paint Base', 'Epoxy Resin', 'Lubricant Oil', 'Rust Inhibitor'],
            'Ceramics' => ['Wall Tiles', 'Floor Tiles', 'Porcelain Blanks', 'Refractory Bricks'],
            'Packaging' => ['Corrugated Cardboard', 'Bubble Wrap', 'Stretch Film', 'Wooden Pallets', 'Steel Strapping'],
        ];

        $this->allSubs = collect();

        foreach ($materialsData as $materialName => $subCategories) {
            $material = Material::create(['name' => $materialName]);

            foreach ($subCategories as $subName) {
                $sub = MaterialSubCategory::create([
                    'material_id' => $material->id,
                    'name' => $subName,
                ]);
                $this->allSubs->push($sub);
            }
        }

        $this->command->info("   → {$this->allSubs->count()} sub-categories across 12 materials");
    }

    // ─── Factories ─────────────────────────────────────────────

    private function seedFactories(): Collection
    {
        $factoriesData = [
            ['name' => 'BlueBridge Manufacturing', 'location' => 'Borg El Arab, Alexandria, Egypt', 'employees' => [
                ['name' => 'Ahmed Mostafa', 'email' => 'ahmed@bluebridge-mfg.com'],
                ['name' => 'Fatma Ali', 'email' => 'fatma@bluebridge-mfg.com'],
                ['name' => 'Youssef Kamal', 'email' => 'youssef@bluebridge-mfg.com'],
                ['name' => 'Mariam Saleh', 'email' => 'mariam@bluebridge-mfg.com'],
            ]],
            ['name' => 'Delta Steel Works', 'location' => '10th of Ramadan City, Sharqia, Egypt', 'employees' => [
                ['name' => 'Hassan Ibrahim', 'email' => 'hassan@deltasteel.com'],
                ['name' => 'Mona Saeed', 'email' => 'mona@deltasteel.com'],
                ['name' => 'Waleed Farouk', 'email' => 'waleed@deltasteel.com'],
            ]],
            ['name' => 'Nile Industrial Group', 'location' => '6th of October City, Giza, Egypt', 'employees' => [
                ['name' => 'Khaled Nasser', 'email' => 'khaled@nileindustrial.com'],
                ['name' => 'Dina Rashid', 'email' => 'dina@nileindustrial.com'],
                ['name' => 'Omar Tarek', 'email' => 'omar@nileindustrial.com'],
                ['name' => 'Sally Magdy', 'email' => 'sally@nileindustrial.com'],
                ['name' => 'Hossam Adel', 'email' => 'hossam@nileindustrial.com'],
            ]],
            ['name' => 'Cairo Plastics Co.', 'location' => 'Helwan Industrial Zone, Cairo, Egypt', 'employees' => [
                ['name' => 'Amr Sayed', 'email' => 'amr@cairoplastics.com'],
                ['name' => 'Nada Fouad', 'email' => 'nada@cairoplastics.com'],
                ['name' => 'Rami Sherif', 'email' => 'rami@cairoplastics.com'],
            ]],
            ['name' => 'Suez Glass Factory', 'location' => 'Ain Sokhna, Suez, Egypt', 'employees' => [
                ['name' => 'Tamer Adel', 'email' => 'tamer@suezglass.com'],
                ['name' => 'Rania Magdy', 'email' => 'rania@suezglass.com'],
                ['name' => 'Ayman Lotfy', 'email' => 'ayman@suezglass.com'],
            ]],
            ['name' => 'Pharaoh Ceramics', 'location' => 'Sadat City, Menoufia, Egypt', 'employees' => [
                ['name' => 'Sameh Rizk', 'email' => 'sameh@pharaohceramics.com'],
                ['name' => 'Enas Gamal', 'email' => 'enas@pharaohceramics.com'],
                ['name' => 'Bassem Nabil', 'email' => 'bassem@pharaohceramics.com'],
                ['name' => 'Hala Mokhtar', 'email' => 'hala@pharaohceramics.com'],
            ]],
            ['name' => 'El-Nasr Automotive Parts', 'location' => 'Obour Industrial Zone, Qalyubia, Egypt', 'employees' => [
                ['name' => 'Tarek Selim', 'email' => 'tarek@elnasrauto.com'],
                ['name' => 'Ghada Kamil', 'email' => 'ghada@elnasrauto.com'],
                ['name' => 'Mahmoud Ezzat', 'email' => 'mahmoud@elnasrauto.com'],
            ]],
            ['name' => 'Alexandria Textiles Mill', 'location' => 'Amreya, Alexandria, Egypt', 'employees' => [
                ['name' => 'Hany Abdelrahman', 'email' => 'hany@alextextiles.com'],
                ['name' => 'Noura Sami', 'email' => 'noura@alextextiles.com'],
                ['name' => 'Kareem Wahba', 'email' => 'kareem@alextextiles.com'],
                ['name' => 'Dalia Fawzy', 'email' => 'dalia@alextextiles.com'],
            ]],
        ];

        $factories = collect();

        foreach ($factoriesData as $data) {
            $factory = Factory::create([
                'name' => $data['name'],
                'location' => $data['location'],
            ]);

            foreach ($data['employees'] as $employee) {
                FactoryEmployee::withoutGlobalScopes()->create([
                    ...$employee,
                    'factory_id' => $factory->id,
                    'password' => $this->password,
                ]);
            }

            $factories->push($factory);
        }

        $totalEmployees = collect($factoriesData)->sum(fn ($f) => count($f['employees']));
        $this->command->info("   → {$factories->count()} factories, {$totalEmployees} employees");

        return $factories;
    }

    // ─── Suppliers ─────────────────────────────────────────────

    private function seedSuppliers(): Collection
    {
        $suppliersData = [
            ['name' => 'Egyptian Steel Supply', 'location' => 'Port Said, Egypt', 'employees' => [
                ['name' => 'Mahmoud Fathy', 'email' => 'mahmoud@egysteelsupply.com'],
                ['name' => 'Laila Hamdy', 'email' => 'laila@egysteelsupply.com'],
                ['name' => 'Emad Shawky', 'email' => 'emad@egysteelsupply.com'],
            ]],
            ['name' => 'Al-Sharq Raw Materials', 'location' => 'Ismailia, Egypt', 'employees' => [
                ['name' => 'Wael Sabry', 'email' => 'wael@alsharq.com'],
                ['name' => 'Heba Mohamed', 'email' => 'heba@alsharq.com'],
                ['name' => 'Tarek Zaki', 'email' => 'tarek@alsharq.com'],
                ['name' => 'Mervat Ashraf', 'email' => 'mervat@alsharq.com'],
            ]],
            ['name' => 'Delta Trading Corp', 'location' => 'Mansoura, Dakahlia, Egypt', 'employees' => [
                ['name' => 'Samir Nabil', 'email' => 'samir@deltatrading.com'],
                ['name' => 'Noha Said', 'email' => 'noha@deltatrading.com'],
                ['name' => 'Fady Maher', 'email' => 'fady@deltatrading.com'],
            ]],
            ['name' => 'Upper Egypt Materials', 'location' => 'Assiut, Egypt', 'employees' => [
                ['name' => 'Ibrahim Gamal', 'email' => 'ibrahim@uppermaterials.com'],
                ['name' => 'Asmaa Refaat', 'email' => 'asmaa@uppermaterials.com'],
            ]],
            ['name' => 'Red Sea Logistics', 'location' => 'Hurghada, Red Sea, Egypt', 'employees' => [
                ['name' => 'Karim Essam', 'email' => 'karim@redsealogistics.com'],
                ['name' => 'Aya Hisham', 'email' => 'aya@redsealogistics.com'],
            ]],
            ['name' => 'Cairo General Supply', 'location' => 'Nasr City, Cairo, Egypt', 'employees' => [
                ['name' => 'Sherif Hassan', 'email' => 'sherif@cairogeneral.com'],
                ['name' => 'Yasmin Alaa', 'email' => 'yasmin@cairogeneral.com'],
                ['name' => 'Mostafa Helmy', 'email' => 'mostafa@cairogeneral.com'],
                ['name' => 'Passant Khaled', 'email' => 'passant@cairogeneral.com'],
            ]],
            ['name' => 'Alexandria Port Supplies', 'location' => 'Dekheila, Alexandria, Egypt', 'employees' => [
                ['name' => 'Adel Mansour', 'email' => 'adel@alexsupplies.com'],
                ['name' => 'Samia Youssef', 'email' => 'samia@alexsupplies.com'],
                ['name' => 'Ramy Galal', 'email' => 'ramy@alexsupplies.com'],
            ]],
            ['name' => 'Sinai Minerals Co.', 'location' => 'El-Arish, North Sinai, Egypt', 'employees' => [
                ['name' => 'Hesham Lotfy', 'email' => 'hesham@sinaiminerals.com'],
                ['name' => 'Nourhan Ali', 'email' => 'nourhan@sinaiminerals.com'],
            ]],
            ['name' => 'Tanta Industrial Supply', 'location' => 'Tanta, Gharbia, Egypt', 'employees' => [
                ['name' => 'Ehab Samir', 'email' => 'ehab@tantasupply.com'],
                ['name' => 'Manal Reda', 'email' => 'manal@tantasupply.com'],
                ['name' => 'Ahmad Ramzy', 'email' => 'ahmad@tantasupply.com'],
            ]],
            ['name' => 'Nile Valley Resources', 'location' => 'Beni Suef, Egypt', 'employees' => [
                ['name' => 'Hazem Fawzy', 'email' => 'hazem@nilevalley.com'],
                ['name' => 'Omnia Yasser', 'email' => 'omnia@nilevalley.com'],
            ]],
        ];

        $suppliers = collect();

        foreach ($suppliersData as $data) {
            $supplier = Supplier::create([
                'name' => $data['name'],
                'location' => $data['location'],
            ]);

            foreach ($data['employees'] as $employee) {
                SupplierEmployee::withoutGlobalScopes()->create([
                    ...$employee,
                    'supplier_id' => $supplier->id,
                    'password' => $this->password,
                ]);
            }

            $suppliers->push($supplier);
        }

        $totalEmployees = collect($suppliersData)->sum(fn ($s) => count($s['employees']));
        $this->command->info("   → {$suppliers->count()} suppliers, {$totalEmployees} employees");

        return $suppliers;
    }

    // ─── Factory ↔ Supplier Connections ────────────────────────

    private function connectFactoriesAndSuppliers(Collection $factories, Collection $suppliers): void
    {
        $totalConnections = 0;

        foreach ($factories as $factory) {
            $count = rand(5, min(8, $suppliers->count()));
            $selected = $suppliers->random($count);
            $factory->suppliers()->attach($selected->pluck('id'));
            $totalConnections += $count;
        }

        $this->command->info("   → {$totalConnections} factory-supplier connections");
    }

    // ─── Supplier Inventory ────────────────────────────────────

    private function seedSupplierInventory(Collection $suppliers): void
    {
        $totalStock = 0;
        $totalTxn = 0;

        $insertNotes = [
            'Shipment received from warehouse',
            'Bulk purchase — new batch',
            'Restocked from port delivery',
            'Monthly scheduled delivery',
            'Emergency restock order',
            'Imported container cleared customs',
            'Transferred from secondary warehouse',
            'Received from manufacturer direct',
            'Wholesale lot acquisition',
            'Quarterly stock replenishment',
        ];

        $useNotes = [
            'Delivered to factory order #%d',
            'Sold to wholesale client',
            'Used for quality testing',
            'Packaging & shipping allocation',
            'Sent to factory per supply request',
            'Dispatched via logistics partner',
            'Client pickup — bulk order',
            'Returned defective batch to manufacturer',
            'Sample sent to prospective buyer',
            'Allocated for pending supply request',
        ];

        foreach ($suppliers as $supplier) {
            // Each supplier stocks 15-30 sub-materials (broad range)
            $selectedSubs = $this->allSubs->random(rand(15, min(30, $this->allSubs->count())));
            $stockRows = [];
            $txnRows = [];

            foreach ($selectedSubs as $sub) {
                $runningQty = 0.0;
                $txnCount = rand(12, 30);

                for ($i = 0; $i < $txnCount; $i++) {
                    $daysAgo = rand(1, 90);
                    $txnDate = now()->subDays($daysAgo)->addHours(rand(6, 20))->addMinutes(rand(0, 59));

                    // First 3 are always inserts to build initial stock
                    if ($i < 3) {
                        $type = 'insert';
                    } else {
                        $type = fake()->randomElement(['insert', 'insert', 'insert', 'use', 'use']);
                    }

                    $qty = $type === 'insert'
                        ? fake()->randomFloat(2, 50, 800)
                        : fake()->randomFloat(2, 10, min(200, max(10, $runningQty * 0.4)));

                    if ($type === 'insert') {
                        $runningQty += $qty;
                    } else {
                        // Don't go below 0
                        $qty = min($qty, max(0, $runningQty - 5));
                        if ($qty <= 0) {
                            $type = 'insert';
                            $qty = fake()->randomFloat(2, 50, 300);
                            $runningQty += $qty;
                        } else {
                            $runningQty -= $qty;
                        }
                    }

                    $note = $type === 'insert'
                        ? $insertNotes[array_rand($insertNotes)]
                        : sprintf($useNotes[array_rand($useNotes)], rand(1000, 9999));

                    $txnRows[] = [
                        'supplier_id' => $supplier->id,
                        'material_sub_category_id' => $sub->id,
                        'type' => $type,
                        'quantity' => round($qty, 2),
                        'notes' => $note,
                        'created_at' => $txnDate,
                        'updated_at' => $txnDate,
                    ];
                }

                $stockRows[] = [
                    'supplier_id' => $supplier->id,
                    'material_sub_category_id' => $sub->id,
                    'quantity' => round(max(0, $runningQty), 2),
                    'created_at' => now()->subDays(90),
                    'updated_at' => now(),
                ];

                $totalStock++;
                $totalTxn += $txnCount;
            }

            // Bulk insert bypasses model events — no scope/recursion issues
            DB::table('supplier_sub_materials')->insert($stockRows);

            foreach (array_chunk($txnRows, 500) as $chunk) {
                DB::table('supplier_sub_material_transactions')->insert($chunk);
            }
        }

        $this->command->info("   → {$totalStock} stock entries, {$totalTxn} transactions");
    }

    // ─── Factory Inventory ─────────────────────────────────────

    private function seedFactoryInventory(Collection $factories): void
    {
        $totalStock = 0;
        $totalTxn = 0;

        $insertNotes = [
            'Supplier delivery received',
            'Emergency restock from supplier',
            'Monthly supply shipment',
            'Warehouse transfer — production floor',
            'Supply request fulfilled',
            'Bulk delivery — quarterly order',
            'Received via supply chain',
            'Inter-factory transfer in',
            'Customs-cleared import shipment',
            'Returned from QC hold — released',
        ];

        $useNotes = [
            'Production batch #%d',
            'Assembly line A — shift %d',
            'Assembly line B — morning run',
            'Quality testing & sampling',
            'Product finishing stage',
            'Packaging & labeling dept.',
            'R&D prototype fabrication',
            'Maintenance & repairs dept.',
            'Customer order #%d fulfillment',
            'Waste/scrap — production defect',
        ];

        foreach ($factories as $factory) {
            // Each factory uses 20-35 sub-materials
            $selectedSubs = $this->allSubs->random(rand(20, min(35, $this->allSubs->count())));
            $stockRows = [];
            $txnRows = [];

            foreach ($selectedSubs as $sub) {
                $safeAmount = fake()->randomFloat(2, 30, 250);
                $runningQty = 0.0;
                $txnCount = rand(15, 35);

                for ($i = 0; $i < $txnCount; $i++) {
                    $daysAgo = rand(1, 90);
                    $txnDate = now()->subDays($daysAgo)->addHours(rand(6, 22))->addMinutes(rand(0, 59));

                    // First 4 always inserts to build initial stock
                    if ($i < 4) {
                        $type = 'insert';
                    } else {
                        // Factories use more than they insert
                        $type = fake()->randomElement(['insert', 'use', 'use', 'use']);
                    }

                    $qty = $type === 'insert'
                        ? fake()->randomFloat(2, 80, 600)
                        : fake()->randomFloat(2, 5, min(150, max(5, $runningQty * 0.3)));

                    if ($type === 'insert') {
                        $runningQty += $qty;
                    } else {
                        $qty = min($qty, max(0, $runningQty - 2));
                        if ($qty <= 0) {
                            $type = 'insert';
                            $qty = fake()->randomFloat(2, 80, 400);
                            $runningQty += $qty;
                        } else {
                            $runningQty -= $qty;
                        }
                    }

                    $note = $type === 'insert'
                        ? $insertNotes[array_rand($insertNotes)]
                        : sprintf($useNotes[array_rand($useNotes)], rand(100, 999));

                    $txnRows[] = [
                        'factory_id' => $factory->id,
                        'material_sub_category_id' => $sub->id,
                        'type' => $type,
                        'quantity' => round($qty, 2),
                        'notes' => $note,
                        'created_at' => $txnDate,
                        'updated_at' => $txnDate,
                    ];
                }

                $stockRows[] = [
                    'factory_id' => $factory->id,
                    'material_sub_category_id' => $sub->id,
                    'quantity' => round(max(0, $runningQty), 2),
                    'safe_amount' => round($safeAmount, 2),
                    'created_at' => now()->subDays(90),
                    'updated_at' => now(),
                ];

                $totalStock++;
                $totalTxn += $txnCount;
            }

            DB::table('factory_sub_materials')->insert($stockRows);

            foreach (array_chunk($txnRows, 500) as $chunk) {
                DB::table('factory_sub_material_transactions')->insert($chunk);
            }
        }

        $this->command->info("   → {$totalStock} stock entries, {$totalTxn} transactions");
    }

    // ─── Supply Requests ───────────────────────────────────────

    private function seedSupplyRequests(Collection $factories, Collection $suppliers): void
    {
        $totalRequests = 0;
        $totalSupplierEntries = 0;
        $totalLogs = 0;

        $rejectionReasons = [
            'Out of stock for this material currently',
            'Cannot fulfill the requested quantity at this time',
            'Delivery schedule conflict — next available in 2 weeks',
            'Price negotiation required before acceptance',
            'Material quality spec does not match our inventory grade',
            'Logistics capacity exceeded for this period',
            'Material reserved for another client order',
            'Supplier warehouse under maintenance',
            'Import shipment delayed at customs',
            'Insufficient quantity — can only supply partial order',
        ];

        foreach ($factories as $factory) {
            $connectedSupplierIds = $factory->suppliers()->pluck('suppliers.id');
            if ($connectedSupplierIds->isEmpty()) {
                continue;
            }

            // Get factory's sub-materials for realistic requests
            $factorySubIds = DB::table('factory_sub_materials')
                ->where('factory_id', $factory->id)
                ->pluck('material_sub_category_id');

            $requestCount = rand(8, 15);

            for ($r = 0; $r < $requestCount; $r++) {
                $subId = $factorySubIds->isNotEmpty()
                    ? $factorySubIds->random()
                    : $this->allSubs->random()->id;

                $sub = $this->allSubs->firstWhere('id', $subId) ?? $this->allSubs->random();

                $status = fake()->randomElement(['pending', 'pending', 'pending', 'accepted', 'accepted', 'rejected']);
                $qtyNeeded = fake()->randomFloat(2, 30, 800);
                $daysAgo = rand(0, 60);
                $createdAt = now()->subDays($daysAgo);

                $request = SupplyRequest::create([
                    'factory_id' => $factory->id,
                    'material_sub_category_id' => $sub->id,
                    'quantity_needed' => $qtyNeeded,
                    'status' => $status,
                    'triggered_by' => fake()->randomElement(['auto', 'auto', 'auto', 'manual']),
                    'accepted_by_supplier_id' => null,
                    'created_at' => $createdAt,
                    'updated_at' => $createdAt,
                ]);

                $totalRequests++;

                // Log: request created
                SupplyRequestLog::create([
                    'supply_request_id' => $request->id,
                    'supplier_id' => null,
                    'action' => 'request_created',
                    'details' => "Supply request created for {$sub->name} (qty: {$qtyNeeded})",
                    'created_at' => $createdAt,
                ]);
                $totalLogs++;

                // Select 2-5 connected suppliers per request
                $selectedSupplierCount = min(rand(2, 5), $connectedSupplierIds->count());
                $selectedSuppliers = $connectedSupplierIds->random($selectedSupplierCount);
                $acceptedSupplierId = null;

                foreach ($selectedSuppliers as $supplierId) {
                    $supplierName = $suppliers->firstWhere('id', $supplierId)?->name ?? "Supplier #{$supplierId}";
                    $supplierStatus = 'pending';
                    $rejectionReason = null;
                    $respondedAt = null;

                    if ($status === 'accepted') {
                        if (! $acceptedSupplierId && fake()->boolean(60)) {
                            $supplierStatus = 'accepted';
                            $acceptedSupplierId = $supplierId;
                            $respondedAt = $createdAt->copy()->addHours(rand(2, 72));
                        } else {
                            $supplierStatus = $acceptedSupplierId ? 'dismissed' : 'pending';
                            $respondedAt = $acceptedSupplierId ? $createdAt->copy()->addHours(rand(2, 72)) : null;
                        }
                    } elseif ($status === 'rejected') {
                        $supplierStatus = 'rejected';
                        $rejectionReason = $rejectionReasons[array_rand($rejectionReasons)];
                        $respondedAt = $createdAt->copy()->addHours(rand(4, 96));
                    }

                    SupplyRequestSupplier::create([
                        'supply_request_id' => $request->id,
                        'supplier_id' => $supplierId,
                        'status' => $supplierStatus,
                        'rejection_reason' => $rejectionReason,
                        'responded_at' => $respondedAt,
                    ]);
                    $totalSupplierEntries++;

                    // Log: sent to supplier
                    SupplyRequestLog::create([
                        'supply_request_id' => $request->id,
                        'supplier_id' => $supplierId,
                        'action' => 'sent_to_supplier',
                        'details' => "Request sent to {$supplierName}",
                        'created_at' => $createdAt->copy()->addMinutes(rand(1, 30)),
                    ]);
                    $totalLogs++;

                    // Log response actions
                    if ($supplierStatus === 'accepted') {
                        SupplyRequestLog::create([
                            'supply_request_id' => $request->id,
                            'supplier_id' => $supplierId,
                            'action' => 'supplier_accepted',
                            'details' => "{$supplierName} accepted the supply request",
                            'created_at' => $respondedAt,
                        ]);
                        $totalLogs++;
                    } elseif ($supplierStatus === 'rejected') {
                        SupplyRequestLog::create([
                            'supply_request_id' => $request->id,
                            'supplier_id' => $supplierId,
                            'action' => 'supplier_rejected',
                            'details' => "{$supplierName} rejected: {$rejectionReason}",
                            'created_at' => $respondedAt,
                        ]);
                        $totalLogs++;
                    } elseif ($supplierStatus === 'dismissed') {
                        SupplyRequestLog::create([
                            'supply_request_id' => $request->id,
                            'supplier_id' => $supplierId,
                            'action' => 'supplier_dismissed',
                            'details' => "{$supplierName} dismissed — another supplier already accepted",
                            'created_at' => $respondedAt,
                        ]);
                        $totalLogs++;
                    }
                }

                // Finalize accepted requests
                if ($status === 'accepted') {
                    if (! $acceptedSupplierId) {
                        $first = SupplyRequestSupplier::where('supply_request_id', $request->id)->first();
                        if ($first) {
                            $first->update([
                                'status' => 'accepted',
                                'responded_at' => $createdAt->copy()->addHours(rand(2, 48)),
                            ]);
                            $acceptedSupplierId = $first->supplier_id;

                            $supplierName = $suppliers->firstWhere('id', $acceptedSupplierId)?->name ?? 'Supplier';
                            SupplyRequestLog::create([
                                'supply_request_id' => $request->id,
                                'supplier_id' => $acceptedSupplierId,
                                'action' => 'supplier_accepted',
                                'details' => "{$supplierName} accepted the request",
                                'created_at' => $first->responded_at,
                            ]);
                            $totalLogs++;
                        }
                    }

                    if ($acceptedSupplierId) {
                        $request->update(['accepted_by_supplier_id' => $acceptedSupplierId]);

                        SupplyRequestLog::create([
                            'supply_request_id' => $request->id,
                            'supplier_id' => $acceptedSupplierId,
                            'action' => 'request_fulfilled',
                            'details' => 'Supply request has been fulfilled',
                            'created_at' => $createdAt->copy()->addDays(rand(1, 5)),
                        ]);
                        $totalLogs++;
                    }
                } elseif ($status === 'rejected') {
                    SupplyRequestLog::create([
                        'supply_request_id' => $request->id,
                        'supplier_id' => null,
                        'action' => 'all_rejected',
                        'details' => 'All contacted suppliers rejected this request',
                        'created_at' => $createdAt->copy()->addDays(rand(3, 7)),
                    ]);
                    $totalLogs++;
                }
            }
        }

        $this->command->info("   → {$totalRequests} requests, {$totalSupplierEntries} supplier entries, {$totalLogs} log entries");
    }

    // ─── Sellers ───────────────────────────────────────────────

    private function seedSellers(): Collection
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
            ['name' => 'Aswan Salvage House', 'email' => 'info@aswansalvage.com'],
            ['name' => 'Mansoura Reclaim Co.', 'email' => 'reclaim@mansouramaterials.com'],
            ['name' => 'Luxor Scrap Exchange', 'email' => 'exchange@luxorscrap.com'],
            ['name' => 'Fayoum Green Recovery', 'email' => 'green@fayoumrecovery.com'],
        ];

        $sellers = collect();

        foreach ($sellersData as $data) {
            $sellers->push(Seller::create([
                ...$data,
                'password' => $this->password,
            ]));
        }

        $this->command->info("   → {$sellers->count()} sellers");

        return $sellers;
    }

    // ─── Selling Offers ────────────────────────────────────────

    private function seedSellingOffers(Collection $sellers, Collection $suppliers): void
    {
        $totalOffers = 0;
        $totalTargets = 0;

        $offerNotes = [
            'Material is in good condition, collected from factory surplus. Pickup available at our warehouse in Cairo.',
            'Scrap metal from demolition site. Bulk discount available for orders over 100 units.',
            'Clean and sorted material, ready for immediate use. Delivery can be arranged within 48 hours.',
            'Excess inventory from our latest project. Quality certified, documentation available on request.',
            'Recycled material, suitable for manufacturing. Inspection welcome at our facility any weekday.',
            'Premium grade surplus — never opened, original packaging. Negotiable for large quantities.',
            'Mixed lot from warehouse clearance. Some minor surface oxidation but structurally sound.',
            'Factory seconds — cosmetic defects only, full structural integrity. 30% below market price.',
            'End-of-line stock clearance. Material specs available. First-come first-served basis.',
            'Imported overstock, stored in climate-controlled facility. Certificate of origin available.',
            'Post-production leftover, minimal handling. Can arrange partial deliveries if needed.',
            'Salvaged from decommissioned facility. Tested and graded. Bulk pricing negotiable.',
            'Seasonal overstock — must clear before month end. Excellent condition, competitive pricing.',
            'Sourced from factory upgrade program. Still meets original manufacturing specifications.',
        ];

        foreach ($sellers as $seller) {
            $offerCount = rand(6, 14);

            for ($o = 0; $o < $offerCount; $o++) {
                $sub = $this->allSubs->random();
                $type = fake()->randomElement(['broadcast', 'broadcast', 'broadcast', 'targeted']);
                $status = fake()->randomElement(['open', 'open', 'open', 'open', 'accepted', 'accepted', 'closed']);
                $daysAgo = rand(0, 75);
                $createdAt = now()->subDays($daysAgo);

                $acceptedBySupplierId = null;
                $acceptedAt = null;

                if ($status === 'accepted') {
                    $acceptedBySupplierId = $suppliers->random()->id;
                    $acceptedAt = $createdAt->copy()->addHours(rand(6, 120));
                }

                $offer = SellingOffer::create([
                    'seller_id' => $seller->id,
                    'material_sub_category_id' => $sub->id,
                    'quantity' => fake()->randomFloat(2, 15, 1200),
                    'price_per_unit' => fake()->boolean(75) ? fake()->randomFloat(2, 5, 750) : null,
                    'notes' => fake()->boolean(70) ? $offerNotes[array_rand($offerNotes)] : null,
                    'type' => $type,
                    'status' => $status,
                    'accepted_by_supplier_id' => $acceptedBySupplierId,
                    'accepted_at' => $acceptedAt,
                    'created_at' => $createdAt,
                    'updated_at' => $acceptedAt ?? $createdAt,
                ]);

                $totalOffers++;

                // Create target entries for targeted offers
                if ($type === 'targeted') {
                    $eligibleSupplierIds = SupplierSubMaterial::withoutGlobalScopes()
                        ->where('material_sub_category_id', $sub->id)
                        ->pluck('supplier_id')
                        ->unique();

                    if ($eligibleSupplierIds->isEmpty()) {
                        $eligibleSupplierIds = $suppliers->random(min(3, $suppliers->count()))->pluck('id');
                    }

                    $targetCount = min(rand(1, 4), $eligibleSupplierIds->count());
                    $targetIds = $eligibleSupplierIds->random($targetCount);

                    foreach ($targetIds as $targetSupplierId) {
                        SellingOfferTarget::create([
                            'selling_offer_id' => $offer->id,
                            'supplier_id' => $targetSupplierId,
                        ]);
                        $totalTargets++;
                    }

                    // If accepted, ensure accepted supplier is in targets
                    if ($acceptedBySupplierId && ! $targetIds->contains($acceptedBySupplierId)) {
                        SellingOfferTarget::create([
                            'selling_offer_id' => $offer->id,
                            'supplier_id' => $acceptedBySupplierId,
                        ]);
                        $totalTargets++;
                    }
                }
            }
        }

        $this->command->info("   → {$totalOffers} offers, {$totalTargets} targeted entries");
    }

    // ─── Summary & Credentials ─────────────────────────────────

    private function printSummary(): void
    {
        $counts = [
            'Admins' => DB::table('admins')->count(),
            'Materials' => DB::table('materials')->count(),
            'Sub-Categories' => DB::table('material_sub_categories')->count(),
            'Factories' => DB::table('factories')->count(),
            'Factory Employees' => DB::table('factory_employees')->count(),
            'Suppliers' => DB::table('suppliers')->count(),
            'Supplier Employees' => DB::table('supplier_employees')->count(),
            'Factory↔Supplier Links' => DB::table('factory_supplier')->count(),
            'Factory Stock Entries' => DB::table('factory_sub_materials')->count(),
            'Factory Transactions' => DB::table('factory_sub_material_transactions')->count(),
            'Supplier Stock Entries' => DB::table('supplier_sub_materials')->count(),
            'Supplier Transactions' => DB::table('supplier_sub_material_transactions')->count(),
            'Supply Requests' => DB::table('supply_requests')->count(),
            'Supply Req. Suppliers' => DB::table('supply_request_suppliers')->count(),
            'Supply Req. Logs' => DB::table('supply_request_logs')->count(),
            'Sellers' => DB::table('sellers')->count(),
            'Selling Offers' => DB::table('selling_offers')->count(),
            'Selling Offer Targets' => DB::table('selling_offer_targets')->count(),
        ];

        $this->command->newLine();
        $this->command->info('╔════════════════════════════════════════════════╗');
        $this->command->info('║              DATABASE SUMMARY                  ║');
        $this->command->info('╠════════════════════════════════════════════════╣');

        foreach ($counts as $label => $count) {
            $this->command->info(sprintf('║  %-28s %6d        ║', $label, $count));
        }

        $total = array_sum($counts);
        $this->command->info('╠════════════════════════════════════════════════╣');
        $this->command->info(sprintf('║  %-28s %6d        ║', 'TOTAL RECORDS', $total));
        $this->command->info('╚════════════════════════════════════════════════╝');
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
