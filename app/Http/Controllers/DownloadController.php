<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\FromArray;

class DownloadController extends Controller
{
    //
    function downloadSampleFile() {

        return Excel::download(new SampleExport, 'sample_import_file.xlsx');
    }

}

class SampleExport implements FromArray
{
    public function array(): array
    {
        return [
            ['CustID', 'StatusID', 'StatusDes', 'Remark', 'ProductID', 'ProductName', 'Target', 'Add', 'CurrentMonth', 'Recommend'],
            ['000005', '', '', 'ไม่ได้รับสิทธิ์', 'อยู่ระหว่างสะสมยอดซื้อ', 'Product A', '100', '50', '10', '10'],
        ];
    }
}
