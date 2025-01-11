<?php

namespace App\Http\Controllers;

use App\Models\Enrollment;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;


class PdfController extends Controller
{
    public function __invoke(Enrollment $enrollment)
    {
        return Pdf::loadView('pdf', ['record' => $enrollment])
            // Pag gusto mo download
//            ->download($enrollment->id. '.pdf')
              ->stream()
            ;
    }
}
