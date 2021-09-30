<x-form-error name="{{ $inputName }}" tag="div">
    @php($inputError = $component->messages($errors)[0] ?? '')

    <x-alert :type="\Rawilk\LaravelBase\Components\Alerts\Alert::ERROR" class="mb-4">
        <p>{{ $inputError }}</p>
    </x-alert>
</x-form-error>
