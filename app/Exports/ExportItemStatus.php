<?php

namespace App\Exports;

use App\parDetails;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ExportItemStatus implements FromCollection, WithHeadings
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
        return parDetails::select('header_id','accountable','document_date','issuance_date','serial_no','doc_ref','stock_code','description','expense_type','dept','doc_status','status','qty','t_cost','added_by')->where('status',$this->r->status)->whereBetween('document_date',[$this->r->from,$this->r->to])->orderBy('header_id','desc')->get()->map(function ($row) {
            return [
                \App\accountabilityHeaders::getNewRefCode($row->header_id) ?? $row->header_id,
                $row->accountable,
                $row->document_date,
                $row->issuance_date ?? $row->document_date,
                $row->serial_no,
                $row->doc_ref,
                $row->stock_code,
                $row->description,
                $row->expense_type ?? '',
                $row->dept,
                $row->doc_status,
                $row->status,
                $row->qty,
                $row->t_cost,
                $row->added_by,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Par #',
            'Accountable',
            'Document Date',
            'Issuance Date',
            'Serial #',
            'Batch/QR #',
            'Stock Code',
            'Description',
            'Expense Type',
            'Department',
            'Document Status',
            'Item Status',
            'Qty',
            'Cost',
            'Added By'
        ];
    }
}
