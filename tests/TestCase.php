<?php

namespace Tests;

use App\Models\User;
use BladeUI\Heroicons\BladeHeroiconsServiceProvider;
use BladeUI\Icons\BladeIconsServiceProvider;
use Filament\FilamentServiceProvider;
use Filament\Forms\FormsServiceProvider;
use Filament\Notifications\NotificationsServiceProvider;
use Filament\Support\SupportServiceProvider;
use Filament\Tables\TablesServiceProvider;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Hash;
use Livewire\LivewireServiceProvider;
use RyanChandler\BladeCaptureDirective\BladeCaptureDirectiveServiceProvider;
use Spatie\Permission\Models\Role;

abstract class TestCase extends BaseTestCase
{

    protected function getPackageProviders($app): array
    {
        return [
            BladeCaptureDirectiveServiceProvider::class,
            BladeHeroiconsServiceProvider::class,
            BladeIconsServiceProvider::class,
            FilamentServiceProvider::class,
            FormsServiceProvider::class,
            LivewireServiceProvider::class,
            NotificationsServiceProvider::class,
            SupportServiceProvider::class,
            TablesServiceProvider::class,
        ];
    }
    protected function setUp(): void {
        parent::setUp();

        $user = User::factory()->create([
            'password' => Hash::make('password')
        ]);
        $role = Role::create(['name' => 'Admin']);

        $user->assignRole($role);

        $this->actingAs($user);
    }

    protected function actingAsAdmin(): User
    {
        $admin = User::factory()->create();
        $admin->assignRole('Admin'); // Ensure the role is assigned properly
        $this->actingAs($admin);

        return $admin;
    }
}
