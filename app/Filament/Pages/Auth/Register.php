<?php

namespace App\Filament\Pages\Auth;

use App\Models\Student;
use App\Models\User;
use Carbon\Carbon;
use Closure;
use Filament\Forms\Components\Component;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Wizard;
use Filament\Pages\Auth\Register as BaseRegister;
use Filament\Forms\Form;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;


class Register extends BaseRegister
{
    protected ?string $maxWidth = '2xl';
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Wizard::make([
                    Wizard\Step::make('Student Details')
                        ->schema([
                            $this->getFirstNameFormComponent(),
                            $this->getMiddleNameFormComponent(),
                            $this->getLastNameFormComponent(),
                            Grid::make(2)->schema([
                                $this->getGenderFormComponent(),
                                $this->getDateOfBirthFormComponent(),
                            ]),
                            $this->getAddressFormComponent(),
                            $this->getContactNumberFormComponent()
                        ]),
                    Wizard\Step::make('Account Details')
                        ->schema([
                            $this->getCustomEmailFormComponent(),
                            $this->getPasswordFormComponent(),
                            $this->getPasswordConfirmationFormComponent(),
                        ]),
                ])->submitAction(new HtmlString(Blade::render(<<<BLADE
                    <x-filament::button
                        type="submit"
                        size="sm"
                        wire:submit="register"
                    >
                        Register
                    </x-filament::button>
                    BLADE))),
            ]);
    }

    protected function handleRegistration(array $data): \Illuminate\Database\Eloquent\Model
    {

        /** @var User $user */
        $data['name'] = trim(
            "{$data['first_name']} " .
            ($data['middle_name'] ? "{$data['middle_name']} " : "") .
            "{$data['last_name']}");

        $user = parent::handleRegistration($data + ['force_renew_password' => false]);
        $user->assignRole('Student');
        $student = Student::create([
            'user_id' => $user->id,
            'student_number' => Str::random(6),
            'first_name' => $data['first_name'],
            'middle_name' => $data['middle_name'],
            'last_name' => $data['last_name'],
            'date_of_birth' => $data['date_of_birth'],
            'contact_number' => $data['contact_number'],
            'gender' => $data['gender'],
            'address' => $data['address'],
        ]);

        unset(
            $data['date_of_birth'],
            $data['first_name'],
            $data['last_name'],
            $data['email'],
            $data['contact_number'],
            $data['gender'],
            $data['address']);

        return $user;
    }

    protected function getFormActions(): array
    {
        return [];
    }

    protected function getCustomEmailFormComponent(): Component {
        return TextInput::make('email')
        ->required()
            ->email()
            ->rules([
                static function ($component) : Closure {
                    return static function (string $attribute, $value, Closure $fail ) use ($component)  {
                        $existingUser = User::withTrashed()
                            ->where('email', Str::lower($value))
                            ->first();

                        // Fails Validation if email already exists on the database
                        if ($existingUser && $existingUser->id !== $component->getRecord()?->user_id) {
                            $fail('Email already exists.');
                        }
                    };
                }
            ]);
    }
    protected function getFirstNameFormComponent(): Component
    {
        return TextInput::make('first_name')
            ->label('First Name')
            ->extraInputAttributes(['onInput' => 'this.value = this.value.toUpperCase()'])
            ->required();
    }

    protected function getMiddleNameFormComponent(): Component
    {
        return TextInput::make('middle_name')
            ->label('Middle Name (Optional)')
            ->placeholder('Leave blank if not applicable')
            ->extraInputAttributes(['onInput' => 'this.value = this.value.toUpperCase()']);
    }

    protected function getLastNameFormComponent(): Component {
        return TextInput::make('last_name')
            ->extraInputAttributes(['onInput' => 'this.value = this.value.toUpperCase()'])
            ->required();
    }

    protected function getAddressFormComponent(): Component {
        return TextInput::make('address')
            ->placeholder('B9 L8 MANCHESTER ST. MOLINO 3, BACOOR CAVITE')
            ->extraInputAttributes(['onInput' => 'this.value = this.value.toUpperCase()'])
            ->required()
            ->columnSpanFull();
    }

    protected function getGenderFormComponent(): Component {
        return Select::make('gender')
            ->options([
                'MALE' => 'Male',
                'FEMALE' => 'Female',
            ])
            ->native(false)
            ->required();
    }
    protected function getDateOfBirthFormComponent(): Component {
        return DatePicker::make('date_of_birth')
            ->required()
            ->before(Carbon::today()->toString());
    }

    protected function getContactNumberFormComponent(): Component {
        return TextInput::make('contact_number')
            ->tel()
            ->prefix('+63')
            ->telRegex('/^9\d{9}$/')
            ->placeholder("9171234567")
            ->required()
            ->rules([
                static function ($component) : Closure {
                    return static function (string $attribute, $value, Closure $fail ) use ($component)  {
                        $existingStudent = Student::withTrashed()
                            ->where('contact_number', Str::lower($value))
                            ->first();

                        // Fails Validation if contact number already exists on the database
                        if ($existingStudent && $existingStudent->id !== $component->getRecord()?->user_id) {
                            $fail('Contact Number already exists.');
                        }
                    };
                }
            ]);
    }




}
