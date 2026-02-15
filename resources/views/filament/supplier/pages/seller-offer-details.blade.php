<div class="space-y-6">
    {{-- Offer Summary --}}
    <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">
        <h3 class="mb-4 text-lg font-semibold text-gray-900 dark:text-white">Offer Details</h3>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <p class="text-sm text-gray-500 dark:text-gray-400">Offer ID</p>
                <p class="font-medium text-gray-900 dark:text-white">#{{ $offer->id }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500 dark:text-gray-400">Status</p>
                <span @class([
                    'inline-flex items-center rounded-full px-2 py-1 text-xs font-medium',
                    'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' =>
                        $offer->status === 'open',
                    'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' =>
                        $offer->status === 'accepted',
                    'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300' =>
                        $offer->status === 'closed',
                ])>
                    {{ ucfirst($offer->status) }}
                </span>
            </div>
            <div>
                <p class="text-sm text-gray-500 dark:text-gray-400">Material</p>
                <p class="font-medium text-gray-900 dark:text-white">
                    {{ $offer->materialSubCategory?->material?->name }}
                </p>
            </div>
            <div>
                <p class="text-sm text-gray-500 dark:text-gray-400">Sub-Category</p>
                <p class="font-medium text-gray-900 dark:text-white">
                    {{ $offer->materialSubCategory?->name }}
                </p>
            </div>
            <div>
                <p class="text-sm text-gray-500 dark:text-gray-400">Quantity Available</p>
                <p class="text-lg font-bold text-primary-600 dark:text-primary-400">
                    {{ number_format($offer->quantity, 2) }}
                </p>
            </div>
            <div>
                <p class="text-sm text-gray-500 dark:text-gray-400">Price per Unit</p>
                <p class="text-lg font-bold text-primary-600 dark:text-primary-400">
                    {{ $offer->price_per_unit ? number_format($offer->price_per_unit, 2) : 'Negotiable' }}
                </p>
            </div>
            <div>
                <p class="text-sm text-gray-500 dark:text-gray-400">Offer Type</p>
                <span @class([
                    'inline-flex items-center rounded-full px-2 py-1 text-xs font-medium',
                    'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200' =>
                        $offer->type === 'broadcast',
                    'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200' =>
                        $offer->type === 'targeted',
                ])>
                    {{ ucfirst($offer->type) }}
                </span>
            </div>
            <div>
                <p class="text-sm text-gray-500 dark:text-gray-400">Posted At</p>
                <p class="font-medium text-gray-900 dark:text-white">
                    {{ $offer->created_at?->format('M d, Y h:i A') }}
                </p>
            </div>
        </div>
    </div>

    {{-- Seller Info --}}
    <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">
        <h3 class="mb-4 text-lg font-semibold text-gray-900 dark:text-white">Seller Information</h3>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <p class="text-sm text-gray-500 dark:text-gray-400">Seller Name</p>
                <p class="font-medium text-gray-900 dark:text-white">
                    {{ $offer->seller?->name ?? '—' }}
                </p>
            </div>
            <div>
                <p class="text-sm text-gray-500 dark:text-gray-400">Seller Email</p>
                <p class="font-medium text-gray-900 dark:text-white">
                    {{ $offer->seller?->email ?? '—' }}
                </p>
            </div>
        </div>
    </div>

    {{-- Notes --}}
    @if ($offer->notes)
        <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">
            <h3 class="mb-4 text-lg font-semibold text-gray-900 dark:text-white">Notes from Seller</h3>
            <div class="prose max-w-none text-gray-700 dark:prose-invert dark:text-gray-300">
                {!! \Illuminate\Support\Str::markdown($offer->notes) !!}
            </div>
        </div>
    @endif

    {{-- Acceptance Info --}}
    @if ($offer->isAccepted())
        <div
            class="rounded-xl border border-green-200 bg-green-50 p-6 shadow-sm dark:border-green-700 dark:bg-green-900/30">
            <h3 class="mb-4 text-lg font-semibold text-green-800 dark:text-green-200">Accepted</h3>
            <div class="grid grid-cols-3 gap-4">
                <div>
                    <p class="text-sm text-green-600 dark:text-green-400">Accepted By</p>
                    <p class="font-medium text-green-900 dark:text-green-100">
                        {{ $offer->acceptedBySupplier?->name ?? '—' }}
                    </p>
                </div>
                <div>
                    <p class="text-sm text-green-600 dark:text-green-400">Supplier Location</p>
                    <p class="font-medium text-green-900 dark:text-green-100">
                        {{ $offer->acceptedBySupplier?->location ?? '—' }}
                    </p>
                </div>
                <div>
                    <p class="text-sm text-green-600 dark:text-green-400">Accepted At</p>
                    <p class="font-medium text-green-900 dark:text-green-100">
                        {{ $offer->accepted_at?->format('M d, Y h:i A') }}
                    </p>
                </div>
            </div>
        </div>
    @endif
</div>
