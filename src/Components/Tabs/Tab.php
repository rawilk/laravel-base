<?php

declare(strict_types=1);

namespace Rawilk\LaravelBase\Components\Tabs;

use Illuminate\Support\Str;
use Rawilk\LaravelBase\Components\BladeComponent;

class Tab extends BladeComponent
{
    public function __construct(
        public null|string $name = '',
        public null|string $href = '#',
        public null|string $id = null, // An alternative to tab name as the id
        public bool $active = false,
        public bool $disabled = false,
    ) {
        $this->resolveId();
    }

    public function optionsToJson(): string
    {
        return json_encode([
            'name' => $this->name,
            'href' => $this->href,
            'id' => $this->id,
            'active' => $this->active && ! $this->disabled,
            'disabled' => $this->disabled,
        ]);
    }

    private function resolveId(): void
    {
        if ($this->id) {
            return;
        }

        if ($this->name) {
            $this->id = Str::slug($this->name);

            return;
        }

        $this->id = 'tab-' . Str::random(6);
    }
}
