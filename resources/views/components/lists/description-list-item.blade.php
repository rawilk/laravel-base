<div {{ $attributes->class('description-list-item | first:pt-0 py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4') }}>
    <dt {{ $componentSlot($label)->attributes->class('text-sm font-medium text-blue-gray-500') }}>{{ $label }}</dt>

    <dd class="mt-1 text-sm text-blue-gray-900 sm:mt-0 sm:col-span-2">{{ $slot }}</dd>
</div>
