<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class accountabilityHeaders extends Model
{
	protected $guarded = [
    	
    ];

    protected $fillable = [
        'employee_id', 'dept_id','is_dept','bis_header_id','ref_par','document_date', 'added_by', 'po', 'doc_status',
        'safety', 'posted_by', 'posted_date', 'unpost_request', 'emp_name', 'p_location', 'p_site', 'doc_ref',
        'rq_no', 'ptype', 'dept', 'isContractor','notes', 'po_no', 'cis_si_no', 'serial_no','reason', 'date_transfer', 'new_ref_code'
    ];
    
	public $table='accountabilityHeaders';
    //public $table = 'v_all_par';

    public function items(){

   		return $this->hasMany('App\Items','accountabilityHeader_id');

   	}

   	public static function generateMonthlyRefCode($documentDate = null)
    {
        $date = $documentDate ? Carbon::parse($documentDate) : Carbon::now();
        $year = $date->format('Y');
        $month = $date->format('m');

        // Count existing records created in the same year and month
        $currentMonthCount = static::whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->count();

        // Increment count for the new record and pad with zeros (4 digits)
        $sequence = str_pad($currentMonthCount + 1, 4, '0', STR_PAD_LEFT);

        return "{$year}-{$month}-{$sequence}";
    }

   	public static function getNewRefCode($id){
   		$ref = accountabilityHeaders::find($id);
   		return $ref->new_ref_code;
   	}

   	public function getRefcodeAttribute(){
   		$ref = $this->parRefCode($this->id);
   		return "{$ref}";
   	}

   	// Par Ref Code
    public function parRefCode($n){     
        $r=strlen($n);
        $e=6 - $r;
        $z="";
        for($x=1;$x<=$e;$x++){
            $z.="0";
        }
        $refcode=$z.$n;
        return $refcode;
    }

    public static function parHeaderId($n){     
        $r=strlen($n);
        $e=6 - $r;
        $z="";
        for($x=1;$x<=$e;$x++){
            $z.="0";
        }
        $refcode=$z.$n;
        return $refcode;
    }

    // public static function employee_status($employee){  
    //     $employees  = file_get_contents("http://172.16.20.27/parv2/api/employee-status.php?emp_id=".$employee);
    //     $array_result = explode('|',$employees);
        
    //     if(in_array($employee, $array_result)){
    //         return 1; // resigned
    //     } else {
    //         return 0; // active 
    //     }
    // }

   
    //end
    public $timestamps = true;
}