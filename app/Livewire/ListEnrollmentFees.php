<?php

namespace App\Livewire;

use App\Models\Enrollment;
use App\Models\EnrollmentFee;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Livewire\Component;

class ListEnrollmentFees extends Component implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    public $enrollmentId;

    public function mount($enrollmentId)
    {
        $this->enrollmentId = $enrollmentId;
    }

    public function table(Table $table): Table {
        return $table
            ->emptyStateHeading('No Fees Recorded')
            ->paginated(false)
            ->query(EnrollmentFee::query()->where('enrollment_id', $this->enrollmentId))
            ->columns([
                textColumn::make('fee.name')
                    ->label('Fee')
                    ->alignment('center'),
                textColumn::make('amount')
                    ->formatStateUsing(fn ($state) => '₱' . $state)
                    ->label('Amount')
                    ->alignment('center'),
            ]);

    }




    public function render()
    {
        return view('livewire.list-enrollment-fees');
    }

}
