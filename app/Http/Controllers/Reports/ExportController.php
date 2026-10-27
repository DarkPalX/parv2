<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Excel;

use App\Exports\ExportDepartmentPar;
use App\Exports\ExportPerDepartment;
use App\Exports\ExportPerDepartmentOpen;
use App\Exports\ExportPerContractor;
use App\Exports\ExportPerIndividual;
use App\Exports\ExportPersonnelPar;
use App\Exports\ExportPerPersonal;
use App\Exports\ExportItemStatus;
use App\Exports\ExportDocStatus;
use App\Exports\ExportParv1Transactions;
use App\Exports\ExportAllTransactions;




class ExportController extends Controller
{   
    public $today;

    public function __construct(){
        $this->today = new Carbon();
    }

    public function personnel_par(Request $req) {
        ob_end_clean(); // this
        ob_start(); // and this
        return Excel::download(new ExportPersonnelPar($req), 'Par All Employees '.$this->today.'.xlsx');
    }

    public function per_personnel_par(Request $req) {
         ob_end_clean(); // this
         ob_start(); // and this
        return Excel::download(new ExportPerPersonal($req), 'Par Per Employee '.$this->today.'.xlsx');
    }

    public function department_par(Request $req) {
         ob_end_clean(); // this
         ob_start(); // and this
        return Excel::download(new ExportDepartmentPar($req), 'Par All Department '.$this->today.'.xlsx');
    }

    public function per_department_par(Request $req) {
         ob_end_clean(); // this
         ob_start(); // and this
        return Excel::download(new ExportPerDepartment($req), 'Par Per Department '.$this->today.'.xlsx');
    }

    public function doc_status(Request $req){
         ob_end_clean(); // this
         ob_start(); // and this
        return Excel::download(new ExportDocStatus($req), $req->status.' Document Status '.$this->today.'.xlsx');
    }

    public function item_status(Request $req){
         ob_end_clean(); // this
         ob_start(); // and this
        return Excel::download(new ExportItemStatus($req), $req->status.' Item Status '.$this->today.'.xlsx');
    }

    public function per_department_export(Request $req) {
        
         ob_end_clean(); // this
         ob_start(); // and this
        return Excel::download(new ExportPerDepartmentOpen($req), 'OPEN Par Per Department '.$this->today.'.xlsx');
    }

    public function per_contractor_export(Request $req) {
         ob_end_clean();
         ob_start();
        return Excel::download(new ExportPerContractor($req), 'OPEN Par Per Contractor '.$this->today.'.xlsx');
    }

    public function parv1_transactions_export(Request $req)
    {
        $dept   = $req->dept ?? 'ALL';
        $search = $req->search ?? 'ALL';

        return Excel::download(
            new ExportParv1Transactions($dept, $search),
            'OPEN Transaction in Par v1 '.$this->today.'.xlsx'
        );
    }

    public function all_transactions_export()
    {
        $export = new ExportAllTransactions();

        return response()->streamDownload(function () use ($export) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, $export->headings());

            foreach ($export->query()->cursor() as $row) {
                fputcsv($handle, $export->map($row));
            }

            fclose($handle);
        }, 'All PAR Transactions '.$this->today.'.csv', [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }


    // public function parv1_transactions_export(Request $req) {
        
    //      ob_end_clean(); // this
    //      ob_start(); // and this
    //     return Excel::download(new ExportParv1Transactions($req), 'OPEN Transaction in Par v1 '.$this->today.'.xlsx');
    // }

    public function per_individual_export(Request $request, $name) {
        \Log::info('EXPORT CONTROLLER');
        // \Log::info($name);
        \Log::info($request);

         ob_end_clean(); // this
         ob_start(); // and this
        // return Excel::download(new ExportPerIndividual($name), 'Department User '.$this->today.'.xlsx');
        return Excel::download(new ExportPerIndividual($request), 'Department User '.$this->today.'.xlsx');
    }

}
