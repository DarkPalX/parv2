<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\Exportable;

class ExportParv1Transactions implements FromCollection, WithHeadings
{
    use Exportable;

    // public $this;

    // public function __construct($this)
    // {
    //     $this = $this;
    // }


    public $dept;
    public $search;

    public function __construct($dept, $search)
    {
        $this->dept   = $dept;
        $this->search = $search;
    }

    public function collection()
    {
        $sql = "SELECT
                    e.empid,
                    e.fullname,
                    e.dept,
                    ah.id,
                    ah.documentDate,
                    i.tracking AS item_tracking,
                    i.name AS item_name,
                    ah.docStatus,
                    ad.status,
                    i.qty AS item_qty,
                    i.price AS item_price,
                    ah.addedBy
                FROM parv1_employee e
                JOIN parv1_accountabilityheader ah
                    ON ah.employeeId = e.id
                JOIN parv1_accountabilitydetail ad
                    ON ad.headerId = ah.id
                JOIN parv1_items i
                    ON i.id = ad.Item
                WHERE ah.docStatus = 'POSTED'
                  AND ad.status = 'OPEN'";

        $bindings = [];

        if ($this->dept && $this->dept !== 'ALL') {
            $sql .= " AND e.dept = ?";
            $bindings[] = $this->dept;
        }

        if (!empty($this->search) && $this->search !== 'ALL') {
            $sql .= " AND (e.empid LIKE ? OR e.fullname LIKE ? OR i.tracking LIKE ? OR ah.refcode LIKE ?)";
            $bindings[] = "%{$this->search}%";
            $bindings[] = "%{$this->search}%";
            $bindings[] = "%{$this->search}%";
            $bindings[] = "%{$this->search}%";
        }

        $sql .= " ORDER BY e.fullname, i.name";

        return collect(DB::select($sql, $bindings));
    }

    public function headings(): array
    {
        return [
            'Emp ID',
            'Accountable',
            'Dept',
            'PAR#',
            'Date',
            'Tracking #',
            'Description',
            'PAR Status',
            'Item Status',
            'Qty',
            'Cost',
            'Added by'
        ];
    }
}
