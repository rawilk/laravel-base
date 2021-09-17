<?php

declare(strict_types=1);

namespace Rawilk\LaravelBase\Components\Feeds;

use Carbon\CarbonInterface;
use Rawilk\LaravelBase\Components\BladeComponent;

class FeedItem extends BladeComponent
{
    public function __construct(
        public null | CarbonInterface $ago = null,
        public bool $convertToUserTimezone = true,
        public string $dateFormat = 'M. d, Y g:i a',
        public $icon = null,
        public $extra = null,
    ) {
        if ($ago instanceof CarbonInterface) {
            $this->ago = $ago->clone();

            if ($convertToUserTimezone) {
                $this->ago = $this->ago->tz(userTimezone());
            }
        }
    }
}
