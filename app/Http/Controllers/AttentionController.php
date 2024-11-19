<?php

namespace App\Http\Controllers;

use App\Http\Controllers\ExternalServices\SISController;
use App\Models\Attention;
use Illuminate\Http\Request;

class AttentionController extends Controller
{

    protected $attention;

    protected $SISController;

    public function __construct(Attention $attention)
    {
        $this->attention = $attention;

        $this->SISController = new SISController();
    }

    public function getDataSIS(Request $request, $sessionId)
    {
        $response  = [];
        $personalData = $this->SISController->getPersonalData($sessionId);
        $detailData = $this->SISController->getDetailsData($sessionId);

        $response = [
            ...$personalData,
            ...$detailData
        ];

        return response()->json($response);
    }
}
