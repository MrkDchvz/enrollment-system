<?php

namespace App\Livewire;

use App\Models\CourseStudent;
use App\Models\Student;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Livewire\Component;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;



class StudentChecklist extends Component implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    public $studentId;

    public function mount($studentId)
    {
        $this->studentId = $studentId;
    }

    public function table(Table $table): Table {
        return $table
            ->query(CourseStudent::query()->where('student_id', $this->studentId))
            ->columns([
                textColumn::make('course.course_code')
                    ->label('Course Code'),
                textColumn::make('course.course_name')
                    ->label('Course Name'),
                textColumn::make('instructor.name')
                    ->label('Instructor'),
                textColumn::make('grade')
                    ->label('Grades'),
            ]);

    }


    public function render()
    {
        return view('livewire.student-checklist');
    }
}
