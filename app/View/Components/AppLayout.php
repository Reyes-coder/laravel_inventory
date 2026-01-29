<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class AppLayout extends Component
{
    /**
     * Get the view / contents that represents the component.
     * Returns admin layout for admins, user layout for regular users
     */
    public function render(): View
    {
        $layout = auth()->user()?->isAdmin()
            ? 'layouts.admin'
            : 'layouts.user';

        return view($layout);
    }
}

