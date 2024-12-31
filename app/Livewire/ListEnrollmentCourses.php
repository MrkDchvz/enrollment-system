<?php

namespace App\Livewire;

use App\Models\CourseEnrollment;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Livewire\Component;

class ListEnrollmentCourses extends Component implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    public $enrollmentId;

    public function mount($enrollmentId) {
        $this->enrollmentId = $enrollmentId;
    }

    public function table(Table $table): Table {
        return $table
            ->emptyStateHeading('No Courses Enrolled')
            ->paginated(false)
            ->query(CourseEnrollment::query()->where("enrollment_id", $this->enrollmentId))
            ->columns([
                textColumn::make('course.course_code')
                    ->label('Course Code'),
                textColumn::make('course_name')
                    ->label('Course Name'),
                textColumn::make('lecture_units')
                    ->label('Lecture Units'),
                textColumn::make('lab_units')
                    ->label('Lab Units'),
                textColumn::make('lecture_hours')
                    ->label('Lecture Hours'),
                textColumn::make('lab_hours')
                    ->label('Lab Hours'),

            ]);
    }

    public function render()
    {
        return view('livewire.list-enrollment-courses');
    }

}
