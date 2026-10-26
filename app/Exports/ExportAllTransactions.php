<?php

namespace App\Exports;

use App\parDetails;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ExportAllTransactions implements FromQuery, WithMapping, WithHeadings
{
    public function query()
    {
        return parDetails::withoutGlobalScopes()
            ->select('header_id', 'emp_name', 'document_date', 'issuance_date', 'serial_no', 'doc_ref', 'stock_code', 'description', 'expense_type', 'dept', 'doc_status', 'status', DB::raw('CASE WHEN qty < 0 THEN 0 ELSE qty END AS qty'), 't_cost', DB::raw('(CASE WHEN qty < 0 THEN 0 ELSE qty END) * t_cost AS total'), 'created_at', 'added_by')
            ->orderBy('header_id', 'desc');
    }

    public function map($row): array
    {
        $financialValues = parDetails::financialValues($row->cost ?? $row->t_cost, $row->qty, parDetails::agingDate($row));

        return [
            (\App\accountabilityHeaders::getNewRefCode($row->header_id) ?? $row->header_id), $row->emp_name, $row->document_date, $row->issuance_date ?? $row->document_date, $financialValues['aging'], $row->serial_no, $row->doc_ref, $row->stock_code, $row->description, $row->expense_type ?? '', $row->dept, $row->doc_status, $row->status, $row->qty, $row->t_cost, $row->total,
            number_format($financialValues['purchase_cost_50'], 2, '.', ''), number_format($financialValues['book_value'], 2, '.', ''), number_format($financialValues['chargeable_cost'], 2, '.', ''), $row->added_by,
        ];
    }

    public function headings(): array
    {
        return ['Par #', 'Accountable', 'Document Date', 'Issuance Date', 'Aging', 'Serial #', 'Batch/QR #', 'Stock Code', 'Description', 'Expense Type', 'Department', 'Document Status', 'Item Status', 'Qty', 'Cost', 'Total Cost', '50% Purchase Cost', 'Book Value', 'Chargeable Cost', 'Encoder'];
    }
}
