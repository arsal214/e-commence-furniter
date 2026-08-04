@php
    /**
     * Shared by the create and edit forms. $review is null on create.
     *
     * Country is not asked for: the store sells to the US, so every review is
     * stamped with the default country and rendered with a US flag. The column
     * stays in place for the day that changes.
     */
    $review ??= null;
    $currentVerified = old('is_verified', $review->is_verified ?? false);
@endphp

<div>
    <label class="block text-sm font-medium text-gray-700 mb-1.5">
        Photo <span class="text-gray-400 font-normal">(optional, max 4MB)</span>
    </label>
    <input type="file" name="image" accept="image/jpeg,image/png,image/webp"
           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm file:mr-3 file:py-1.5 file:px-3 file:rounded file:border-0 file:bg-gray-100 file:text-gray-700 file:text-sm focus:outline-none focus:border-[#bb976d]">

    @if($review?->image_url)
        <div class="flex items-center gap-3 mt-2">
            <img src="{{ $review->image_url }}" alt="Current review photo" class="w-14 h-14 rounded object-cover border border-gray-200">
            <label class="flex items-center gap-1.5 text-xs text-red-600">
                <input type="checkbox" name="remove_image" value="1" class="rounded border-gray-300">
                Remove this photo
            </label>
        </div>
        <p class="text-xs text-gray-400 mt-1">Choosing a new file replaces it.</p>
    @endif
</div>

<label class="flex items-start gap-2.5 cursor-pointer">
    <input type="checkbox" name="is_verified" value="1" {{ $currentVerified ? 'checked' : '' }}
           class="mt-0.5 rounded border-gray-300 text-[#bb976d] focus:ring-[#bb976d]">
    <span class="text-sm">
        <span class="font-medium text-gray-700">Verified purchase</span>
        <span class="block text-xs text-gray-400">
            Shows a green tick on the review. Customer-written reviews set this automatically from the buyer's paid
            orders — only tick it here when the purchase genuinely happened.
        </span>
    </span>
</label>
