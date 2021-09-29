<div x-data="{ copied: false, text: {{ $textIsArray ? $text : "'" . $text . "'" }} }"
     x-on:click="() => {
        if (! copied) {
            $clipboard(Array.isArray(text) ? text.join('\n') : text);
            copied = true;
        }
     }"
     x-init="$watch('copied', value => { value && setTimeout(() => { copied = false }, 3000) })"
     {{ $attributes->class('inline-flex items-center relative justify-center border rounded-md p-2 transition-colors group') }}
     x-bind:class="{
        'hover:bg-gray-100 border-gray-300': ! copied,
        'border-green-300': copied,
     }"
     title="{{ $title }}"
     role="button"
>
    <div x-show="copied"
         class="tooltip right-full mr-3 py-2 px-2 whitespace-normal	text-left"
         data-popper-placement="left"
         role="alert"
         style="display: none;"
    >
        <span>{{ $message }}</span>

        <span class="tooltip-arrow"></span>
    </div>

    <x-dynamic-component
        :component="$icon"
        x-show="! copied"
        role="button"
        class="h-5 w-5 text-gray-500 group-hover:text-gray-700 cursor-pointer transition duration-150 ease-in-out"
    />

    <x-dynamic-component
        :component="$copiedIcon"
        x-show="copied"
        class="h-5 w-5 text-green-500 transition"
    />
</div>
