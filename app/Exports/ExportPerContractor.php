<?php

namespace App\Exports;

use App\parDetails;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ExportPerContractor implements FromCollection, WithHeadings
{
    protected $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function collection()
    {
        $query = parDetails::where('status', 'OPEN')
            ->where('isContractor', 1)
            ->select(
                'header_id', 'emp_name', 'document_date', 'issuance_date',
                'detail_serial_no', 'doc_ref', 'stock_code', 'description',
                'expense_type', 'dept', 'doc_status', 'status', 'qty',
                't_cost', 'added_by', 'details_id'
            );

        if ($this->request->contractor && $this->request->contractor !== 'ALL') {
            $query->where('emp_name', $this->request->contractor);
        }

        return $query->orderBy('emp_name')->orderBy('header_id', 'desc')->get()->map(function ($row) {
            $financialValues = parDetails::financialValues(
                $row->cost ?? $row->t_cost,
                $row->qty,
                parDetails::agingDate($row)
            );

            return [
                \App\accountabilityHeaders::getNewRefCode($row->header_id) ?? $row->header_id,
                $row->emp_name,
                $row->document_date,
                $row->issuance_date ?? $row->document_date,
                $financialValues['aging'],
                $row->detail_serial_no,
                $row->doc_ref,
                $row->stock_code,
                $row->description,
                $row->expense_type ?? '',
                $row->dept,
                $row->doc_status,
                $row->status,
                $row->qty,
                $row->t_cost,
                number_format($financialValues['purchase_cost_50'], 2, '.', ''),
                number_format($financialValues['book_value'], 2, '.', ''),
                number_format($financialValues['chargeable_cost'], 2, '.', ''),
                $row->added_by,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'PAR #', 'Contractor', 'Document Date', 'Issuance Date', 'Aging',
            'Serial #', 'Batch/QR #', 'Stock Code', 'Description', 'Expense Type',
            'Department', 'Document Status', 'Item Status', 'Qty', 'Cost',
            '50% Purchase Cost', 'Book Value', 'Chargeable Cost', 'Added By'
        ];
    }
}
