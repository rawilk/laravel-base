<?php

declare(strict_types=1);

namespace App\Http\ViewComposers;

use Illuminate\View\View;
use Rawilk\LaravelBase\Components\Alerts\Alert;

final class SessionAlertViewComposer
{
    public function compose(View $view): void
    {
        $view->with([
            'sessionAlertTypes' => [
                Alert::SUCCESS,
                Alert::WARNING,
                Alert::ERROR,
            ],
        ]);
    }
}
