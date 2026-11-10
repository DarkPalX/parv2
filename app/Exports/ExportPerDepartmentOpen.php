<?php

namespace App\Exports;

use App\parDetails;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ExportPerDepartmentOpen implements FromQuery, WithMapping, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public $r;

    public function __construct($request)
    {
        $this->r = $request;

    }

    public function query()
    {
        $query = parDetails::select(
            'dept', 'employee_id', 'accountable', 'header_id', 'document_date', 'issuance_date', 'detail_serial_no',
            'doc_ref', 'stock_code', 'description', 'expense_type', 'doc_status', 'status',
            'qty', 'cost', 'created_at', 'added_by'
        )
            ->where('status', 'OPEN')
            ->orderBy('dept')
            ->orderBy('accountable')
            ->orderBy('header_id');

        if ($this->r->dept != 'ALL') {
            $url = env('API_URL') . "hris-get-employees-api.php?dept=" . urlencode($this->r->dept);
            $data = @file_get_contents($url);
            $employeeIds = json_decode($data, true) ?: [];
            $query->whereIn('employee_id', $employeeIds);
        }

        return $query;
    }

    public function map($row): array
    {
        $financialValues = parDetails::financialValues($row->cost, $row->qty, parDetails::agingDate($row));

        return [
            $this->r->dept == 'ALL' ? $row->dept : $this->r->dept,
            $row->accountable,
            $row->refcode,
            $row->document_date,
            $row->issuance_date ?? $row->document_date,
            $financialValues['aging'],
            $row->detail_serial_no,
            $row->doc_ref,
            $row->stock_code,
            $row->description,
            $row->expense_type ?? '',
            strtoupper($row->doc_status),
            $row->status,
            $row->qty,
            $row->cost,
            number_format($financialValues['purchase_cost_50'], 2, '.', ''),
            number_format($financialValues['book_value'], 2, '.', ''),
            number_format($financialValues['chargeable_cost'], 2, '.', ''),
            $row->added_by,
        ];
    }

    public function headings(): array
    {
        return [
            'Department',
            'Accountable',
            'Par #',            
            'Document Date',
            'Issuance Date',
            'Aging',
            'Serial #',
            'Batch/QR #',
            'Stock Code',
            'Description',
            'Expense Type',
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
