<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exports\KaryawanImportTemplate;
use Maatwebsite\Excel\Facades\Excel;

class KaryawanTemplateController extends Controller
{
    /**
     * Download template import karyawan
     *
     * @return \Illuminate\Http\Response
     */
    public function downloadTemplate()
    {
        return Excel::download(new KaryawanImportTemplate, 'karyawan_import_template.xlsx');
    }
}
