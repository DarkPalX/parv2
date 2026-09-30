<?php

namespace App\Exports;

use App\parDetails;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ExportPerDepartmentOpen implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public $r;

    public function __construct($request)
    {
        $this->r = $request;

    }

    public function collection()
    {
        if ($this->r->dept == 'ALL') {
            return parDetails::select(
                'dept', 'accountable', 'header_id', 'document_date', 'serial_no', 
                'doc_ref', 'stock_code', 'description', 'doc_status', 'status', 
                'qty', 't_cost', 'added_by'
            )
            ->where('status', 'OPEN')
            ->orderBy('dept')
            ->orderBy('accountable')
            ->get();
        } 
        else {
            $url = env('APP_URL') . "/parv2_latest/api/hris-get-employees-api.php?dept=" . urlencode($this->r->dept);
            // $url = env('APP_URL') . "/parv2/api/hris-get-employees-api.php?dept=" . urlencode($this->r->dept);
            
            $data = @file_get_contents($url);
            
            $employeeIds = json_decode($data, true) ?: [];

            $collection = parDetails::select(
                'dept',
                'accountable',
                'header_id',
                'document_date',
                'serial_no',
                'doc_ref',
                'stock_code',
                'description',
                'doc_status',
                'status',
                'qty',
                't_cost',
                'added_by'
            )
            ->whereIn('employee_id', $employeeIds)
            ->where('status', 'OPEN')
            ->orderBy('dept')
            ->orderBy('accountable')
            ->get();
 
            // Replace dept value
            foreach ($collection as $row) {
                $row->dept = $this->r->dept;
            }
 
            return $collection;

            
            // return parDetails::select(
            //     'dept', 'accountable', 'header_id', 'document_date', 'serial_no', 
            //     'doc_ref', 'stock_code', 'description', 'doc_status', 'status', 
            //     'qty', 't_cost', 'added_by'
            // )
            // ->whereIn('employee_id', $employeeIds)
            // ->where('status', 'OPEN')
            // ->orderBy('dept')
            // ->orderBy('accountable')
            // ->get();
        }
    }

    // public function collection()
    // {
        
    //     if($this->r->dept == 'ALL'){
    //         return parDetails::select('dept','accountable','header_id','document_date','serial_no','doc_ref','stock_code','description','doc_status','status','qty','t_cost','added_by')->where('status','OPEN')->orderBy('dept')->orderBy('accountable')->get();
            
    //     }
    //     else{
    //         //$dept = str_replace(':', '/', $this->r->dept);        
    //         return parDetails::select('dept','accountable','header_id','document_date','serial_no','doc_ref','stock_code','description','doc_status','status','qty','t_cost','added_by')->where('dept',$this->r->dept)->where('status','OPEN')->orderBy('dept')->orderBy('accountable')->get();
    //     }

    // }

    public function headings(): array
    {
        return [
            'Department',
            'Accountable',
            'Par #',            
            'Document Date',
            'Serial #',
            'Batch/QR #',
            'Stock Code',
            'Description',            
            'Document Status',
            'Item Status',
            'Qty',
            'Cost',
            'Added By'
        ];
    }
}
