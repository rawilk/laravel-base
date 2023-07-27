<?php

declare(strict_types=1);

namespace Rawilk\LaravelBase\Components\Elements;

use Rawilk\LaravelBase\Components\BladeComponent;

class Topbar extends BladeComponent
{
    public function __construct(
        public ?string $searchPlaceholder = null,
        public string $searchModel = 'filters.search',
        public bool $showPerPage = true,
        public string $perPageModel = 'perPage',
        public array $perPageOptions = [10, 25, 50],
        public bool $showColumns = true,
        public array $hideableColumns = [],
        public array $hiddenColumns = [],
        public $filters = null,
    ) {
        if (is_null($searchPlaceholder)) {
            $this->searchPlaceholder = __('base::messages.labels.form.search_placeholder');
        }
    }
}
