<?php

namespace App\Http\Controllers;

use App\Models\ServiceVisit;
use Barryvdh\DomPDF\Facade\Pdf;

class ServiceReportController extends Controller
{
    public function download(ServiceVisit $serviceVisit)
    {
        abort_unless(
            $serviceVisit->subscription && $serviceVisit->subscription->user_id === auth()->id(),
            403
        );

        $pdf = Pdf::loadView('pdfs.service-report', ['visit' => $serviceVisit]);

        return $pdf->download('service-report-'.$serviceVisit->id.'.pdf');
    }
}
