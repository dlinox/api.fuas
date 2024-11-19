<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class FUAController extends Controller
{
    //

    public function HealthFUA(Request $request)
    {
        
        $pdf = PDF::loadView('pdf.fuas.healthFUA');

        //margenes 
        $pdf->setPaper('A4', 'portrait');

        return $pdf->stream('healthFUA.pdf');
    }
}
