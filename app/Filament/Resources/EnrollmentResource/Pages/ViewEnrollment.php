<?php

namespace App\Filament\Resources\EnrollmentResource\Pages;

use App\Filament\Resources\EnrollmentResource;
use EightyNine\Approvals\Models\ApprovableModel;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\ViewRecord;

class ViewEnrollment extends ViewRecord
{

    protected static string $resource = EnrollmentResource::class;

}
