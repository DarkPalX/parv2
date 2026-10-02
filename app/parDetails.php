<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Session;
use Auth;
use DB;
use Carbon\Carbon;



class parDetails extends Model
{
	protected $guarded = [
    	
    ];
    
	public $table='v_par_details';

	public function getRefcodeAttribute(){
		$ref = $this->parRefCode($this->header_id);
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

	public static function unreturned($id)
    {
        $par = parDetails::where('employee_id',$id)->where('status','OPEN')->first();
        
        return $par->qty;
    }

    public static function totalActiveAccountability(){
    	$total = parDetails::where('doc_status','<>','closed')->count();

    	return $total;
    }

    public static function docStatus($status){
    	$total = parDetails::where('doc_status',$status)->count();

    	return $total;

    }

	protected static function boot(){
		
		parent::boot();

		
		static::addGlobalScope('header_id', function (Builder $builder){
			if(Auth::user()->is_dept == 1){
				$builder->where('dept','=', Auth::user()->dept);
			}
			else {

			}
			
		});
	}

	/**
	 * Return the policy values for an item using straight-line depreciation
	 * over five years (60 completed calendar months).
	 */
	public static function financialValues($cost, $qty = 1, $purchaseDate = null)
	{
		$unitCost = max(0, (float) $cost);
		$quantity = max(0, (float) $qty);
		$months = 0;

		if ($purchaseDate) {
			try {
				$months = min(60, max(0, Carbon::parse($purchaseDate)->diffInMonths(Carbon::today())));
			} catch (\Exception $e) {
				$months = 0;
			}
		}

		$unitBookValue = max(0, $unitCost - (($unitCost / 60) * $months));
		$halfPurchaseCost = ($unitCost * 0.50) * $quantity;
		$bookValue = $unitBookValue * $quantity;

		return [
			'purchase_cost_50' => $halfPurchaseCost,
			'book_value' => $bookValue,
			'chargeable_cost' => max($halfPurchaseCost, $bookValue),
			'elapsed_months' => $months,
		];
	}
 	
}
