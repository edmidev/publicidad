<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use stdClass;

class ReportController extends Controller
{
    public function fetchYearlyReport(Request $request)
    {
        $currentYear = date('Y');

        $newClients = Client::select(
            DB::raw('MONTH(created_at) as month'),
            DB::raw('COUNT(id) as total')
        )
            ->where(DB::raw('YEAR(created_at)'), $currentYear)
            ->groupBy('month')
            ->get();

        return response()->json([
            'status' => 1,
            'data' => $this->formatDataToGraph($newClients)
        ]);
    }

    private function formatDataToGraph($fulldata)
    {
        $result = [];
        foreach ($fulldata as $tempData) {
            $result[$tempData->month] = $tempData->total;
        }

        $data = [];
        $ene = new stdClass();
        $ene->mes = 'Ene';
        $ene->total = $result[1] ?? 0;
        $data[] = $result[1] ?? 0;

        $feb = new stdClass();
        $feb->mes = 'Feb';
        $feb->total = $result[2] ?? 0;
        $data[] = $result[2] ?? 0;

        $mar = new stdClass();
        $mar->mes = 'Mar';
        $mar->total = $result[3] ?? 0;
        $data[] = $result[3] ?? 0;

        $abr = new stdClass();
        $abr->mes = 'Abr';
        $abr->total = $result[4] ?? 0;
        $data[] = $result[4] ?? 0;

        $may = new stdClass();
        $may->mes = 'May';
        $may->total = $result[5] ?? 0;
        $data[] = $result[5] ?? 0;

        $jun = new stdClass();
        $jun->mes = 'Jun';
        $jun->total = $result[6] ?? 0;
        $data[] = $result[6] ?? 0;

        $jul = new stdClass();
        $jul->mes = 'Jul';
        $jul->total = $result[7] ?? 0;
        $data[] = $result[7] ?? 0;

        $ago = new stdClass();
        $ago->mes = 'Ago';
        $ago->total = $result[8] ?? 0;
        $data[] = $result[8] ?? 0;

        $sep = new stdClass();
        $sep->mes = 'Sep';
        $sep->total = $result[9] ?? 0;
        $data[] = $result[9] ?? 0;

        $oct = new stdClass();
        $oct->mes = 'Oct';
        $oct->total = $result[10] ?? 0;
        $data[] = $result[10] ?? 0;

        $nov = new stdClass();
        $nov->mes = 'Nov';
        $nov->total = $result[11] ?? 0;
        $data[] = $result[11] ?? 0;

        $dic = new stdClass();
        $dic->mes = 'Dic';
        $dic->total = $result[12] ?? 0;
        $data[] = $result[11] ?? 0;

        return $data;
    }

    public function getUsersWithBirthdayToday()
    {
        $today = now()->format('m-d');
        $clientsWithBirthday = Client::whereRaw("DATE_FORMAT(birthday, '%m-%d') = ?", [$today])->select(['birthday', 'names'])->get();

        return response()->json([
            'birthdays' => $clientsWithBirthday
        ]);
    }
}
