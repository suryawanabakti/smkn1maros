<?php

namespace App\Filament\Pages\Auth;

use Filament\Pages\Auth\Login as BaseLogin;
use Illuminate\Support\Facades\Blade;

class Login extends BaseLogin
{
    protected function getLayoutData(): array
    {
        return [
            ...parent::getLayoutData(),
            'backgroundImageUrl' => asset('images/login-background.jpg'), // Change this to your image path
        ];
    }

    public function getViewData(): array
    {
        return [
            ...parent::getViewData(),
            'backgroundImageUrl' => asset('images/login-background.jpg'), // Change this to your image path
        ];
    }

    protected function getAuthenticateFormAction(): Action
    {
        return parent::getAuthenticateFormAction()
            ->color('primary'); // Customize button color if needed
    }

    public function render(): View
    {
        return view('filament.pages.auth.login', $this->getViewData())
            ->layout('filament.pages.auth.login-layout', $this->getLayoutData());
    }
}
