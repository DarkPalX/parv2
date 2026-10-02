<?php

namespace App\Exports;

use App\parDetails;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Facades\DB;

class ExportPerPersonal implements FromCollection, WithHeadings
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
        
        $collection = parDetails::select('header_id','emp_name','document_date','serial_no','doc_ref','stock_code','description','dept','doc_status','status',DB::raw('CASE WHEN qty < 0 THEN 0 ELSE qty END AS qty'),'cost',DB::raw('(CASE WHEN qty < 0 THEN 0 ELSE qty END) * cost AS total'),'created_at','added_by')->where('emp_name',$this->r->emp)->whereBetween('document_date',[$this->r->from,$this->r->to])->orderBy('header_id','desc')->get();

        return $collection->map(function ($row) {
            $financialValues = parDetails::financialValues($row->cost, $row->qty, $row->created_at ?? $row->document_date);
            return [$row->header_id, $row->emp_name, $row->document_date, $financialValues['elapsed_months'].' mos', $row->serial_no, $row->doc_ref, $row->stock_code, $row->description, $row->dept, $row->doc_status, $row->status, $row->qty, $row->cost, $row->total, number_format($financialValues['purchase_cost_50'], 2, '.', ''), number_format($financialValues['book_value'], 2, '.', ''), number_format($financialValues['chargeable_cost'], 2, '.', ''), $row->added_by];
        });
        // return parDetails::select('header_id','emp_name','document_date','serial_no','doc_ref','stock_code','description','dept','doc_status','status','qty','cost',DB::raw('qty * cost as total'),'added_by')->where('emp_name',$this->r->emp)->whereBetween('document_date',[$this->r->from,$this->r->to])->orderBy('header_id','desc')->get();

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
            'Total Cost',
            '50% Purchase Cost',
            'Book Value',
            'Chargeable Cost',
            'Encoder'
        ];
    }
}
