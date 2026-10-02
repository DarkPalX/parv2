<?php

namespace App\Exports;

use App\parDetails;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ExportPerDepartment implements FromCollection, WithHeadings
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
        
        if($this->r->dept == 'No-dept'){
            $collection = parDetails::select('header_id','accountable','document_date','serial_no','doc_ref','stock_code','description','dept','doc_status','status','qty','t_cost','created_at','added_by')->whereBetween('document_date',[$this->r->from,$this->r->to])->orderBy('header_id','desc')->get();
            
        }
        else{
            $dept = str_replace(':', '/', $this->r->dept);        
            $collection = parDetails::select('header_id','accountable','document_date','serial_no','doc_ref','stock_code','description','dept','doc_status','status','qty','t_cost','created_at','added_by')->where('dept',$dept)->whereBetween('document_date',[$this->r->from,$this->r->to])->orderBy('header_id','desc')->get();
        }

        return $collection->map(function ($row) {
            $financialValues = parDetails::financialValues($row->cost ?? $row->t_cost, $row->qty, $row->created_at ?? $row->document_date);
            return [$row->header_id, $row->accountable, $row->document_date, $financialValues['elapsed_months'].' mos', $row->serial_no, $row->doc_ref, $row->stock_code, $row->description, $row->dept, $row->doc_status, $row->status, $row->qty, $row->t_cost, number_format($financialValues['purchase_cost_50'], 2, '.', ''), number_format($financialValues['book_value'], 2, '.', ''), number_format($financialValues['chargeable_cost'], 2, '.', ''), $row->added_by];
        });

    }

    public function headings(): array
    {
        return [
            'Par #',
            'Accountable',
            'Document Date',
            'Aging',
            'Serial #',
            'Batch/QR #',
            'Stock Code',
            'Description',
            'Department',
            'Document Status',
            'Item Status',
            'Qty',
            'Cost',
            '50% Purchase Cost',
            'Book Value',
            'Chargeable Cost',
            'Added By'
        ];
    }
}
