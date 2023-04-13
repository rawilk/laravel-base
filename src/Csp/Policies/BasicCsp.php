<?php

declare(strict_types=1);

namespace Rawilk\LaravelBase\Csp\Policies;

use Rawilk\LaravelBase\Csp\Enums\CspDirective;
use Rawilk\LaravelBase\Csp\Enums\CspKeyword;

class BasicCsp extends CspPolicy
{
    public function configure(): void
    {
        $this
            ->addDirective(CspDirective::Base, CspKeyword::Self)
            ->addDirective(CspDirective::Connect, CspKeyword::Self)
            ->addDirective(CspDirective::Default, CspKeyword::Self)
            ->addDirective(CspDirective::FormAction, CspKeyword::Self)
            ->addDirective(CspDirective::Img, CspKeyword::Self)
            ->addDirective(CspDirective::Media, CspKeyword::Self)
            ->addDirective(CspDirective::Object, CspKeyword::None)
            ->addDirective(CspDirective::Script, CspKeyword::Self)
            ->addDirective(CspDirective::Style, CspKeyword::Self)
            ->addNonceForDirective(CspDirective::Script)
            ->addNonceForDirective(CspDirective::Style);
    }
}
