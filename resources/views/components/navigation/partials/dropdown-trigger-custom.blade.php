<div {{ $triggerAttributesToString() }} {{ $componentSlot($trigger ?? null)->attributes }}>
    {{ $trigger ?? '' }}
</div>
