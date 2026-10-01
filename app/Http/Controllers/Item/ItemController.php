<?php

namespace App\Http\Controllers\Item;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests;
use Carbon\Carbon;
use Auth;
use DB;

use App\StockedItems;
use App\SelectMaster;
use App\parDetails;
use App\Items;
use App\Uom;


class ItemController extends Controller 
{   
    public function create_stock_code(Request $req)
    {
        $stock = StockedItems::where('stock_code', '=', $req->stock_code)->exists();

        if($stock){
            return back()->with('failed','Stock code already in the list.');
        } else {
               $query = StockedItems::create([
                'stock_type' => $req->stock_type,
                'inv_code' => $req->inv_code,
                'stock_code' => $req->stock_code,
                'description' => $req->description,
                'oem_id' => $req->oem,
                'uom' => $req->uom,
                'uploaded_by' => Auth::user()->domainAccount,
                'uploaded_at' => Carbon::now()
            ]); 

            return back()->with('success','Stock Code successfully created.');
        }
    }

    public function create_item($stock_code)
    {
        $uom_data = Uom::orderBy('code','asc')->get();
        $stock = StockedItems::where('stock_code',$stock_code)->first();

        return view('item.add',compact('stock','uom_data'));
    }

    public function item_delete(Request $req)
    {
        Items::find($req->iid)->delete();

        return back()->with('Item deleted successfully.');
    }

    public function delete_stock_code(Request $req)
    {
        StockedItems::find($req->sid)->delete();

        return back()->with('success','Stock code deleted successfully.');
    }

    public function store(Request $req)
    {   
        $req->validate([
            'expense_type' => 'required|in:CAPEX,OPEX',
            'capex_code' => 'required_if:expense_type,CAPEX|max:50',
        ]);

        // CAPEX codes must never be carried by OPEX items.
        $capexCode = $req->expense_type === 'CAPEX' ? $req->capex_code : null;

        if($req->qty == 0){

            // if(Items::where('stock_code',$req->stock_code)->exists()){
            //     return back()->with('failed','Stock code is already in the list.');
            // } else {
                $serial = new Items();
                
                $serial->stock_type = $req->stock_type;
                $serial->inv_code = $req->i_inv_code;
                $serial->stock_code = $req->stock_code;
                $serial->description = $req->desc;
                $serial->oem_id = $req->oem_id;
                $serial->uom = $req->item_kind == 1 || 3 ? $req->uom_input : $req->uom_option;
                $serial->item_kind = 1;
                $serial->expense_type = $req->expense_type;
                $serial->capex_code = $capexCode;
                $serial->other_specs = $req->other_specs;
                $serial->cost = $req->cost;
                $serial->po_no = $req->po;
                $serial->dr_no = $req->dr_no;
                $serial->invoice_no = $req->invc_no;
                $serial->is_verified = 0;
                $serial->qty = $req->qty;
                $serial->added_by = Auth::user()->domainAccount;
                $serial->save();
            //}
        } else {
            $data = $req->all();
            $serials = $data['serial'];
            
            foreach($serials as $key => $i){
                $serial = new Items();
                
                $serial->serial_no = $serials[$key];
                $serial->stock_type = $req->stock_type;
                $serial->inv_code = $req->i_inv_code;
                $serial->stock_code = $req->stock_code;
                $serial->description = $req->desc;
                $serial->oem_id = $req->oem_id;
                $serial->uom = $req->item_kind == 1  ? $req->uom_input : $req->uom_option;
                $serial->item_kind = $req->item_kind;
                $serial->expense_type = $req->expense_type;
                $serial->capex_code = $capexCode;
                $serial->other_specs = $req->other_specs;
                $serial->cost = $req->cost;
                $serial->po_no = $req->po;
                $serial->dr_no = $req->dr_no;
                $serial->invoice_no = $req->invc_no;
                $serial->is_verified = 0;
                $serial->qty = 1;
                $serial->added_by = Auth::user()->domainAccount;
                $serial->save();
            }
        }

        return back()->with('success','Item successfully saved!');
    }

    public function non_stock()
    {
        $items = Items::where('stock_code','=',NULL)->orderBy('id','desc')->paginate(20);
        return view('Item.non_stock',compact('items'));
    }

    public function ppe()
    {
        $items = Items::where('inv_code','=','SAF')->orderBy('id','desc')->paginate(20);
        return view('Item.ppe',compact('items'));
    }

    public function stocked()
    {
        $items = Items::where('stock_code','<>','')->orderBy('id','desc')->paginate(20);

        return view('Item.stocked_items',compact('items'));
    }

    public function stock_code()
    {   
        $uom_data  = Uom::orderBy('code','asc')->get();
        $inv_codes = StockedItems::distinct()->select('inv_code')->get();
        $stock_typ = StockedItems::distinct()->select('stock_type')->get();
        $items     = StockedItems::orderBy('id','desc')->paginate(20);

        return view('maintenance.stock_code',compact('items','inv_codes','uom_data','stock_typ'));
    }

    public function details($item)
    {
        $history = parDetails::where('item_id',$item)->where('status','OPEN')->orderBy('header_id','desc')->get();
        $par = parDetails::where('item_id',$item)->where('status','OPEN')->first();
        $hid = $par->header_id;
        $item    = Items::where('id','=',$item)->first(); 

        return view('Item.details',compact('item','history','hid'));
    }

    public function add()
    {
        $uom_data = Uom::orderBy('code','asc')->get();
        
        return view('Item.add',compact('uom_data')); 
    }

    public function edit($id)
    {
        $inv_data   = StockedItems::distinct()->select('inv_code')->get();
        $stock_data = SelectMaster::where('select_option','stock_type')->orderBy('id','asc')->get();
        $uom_data   = Uom::orderBy('code','asc')->get();

        $item = Items::where('id',$id)->first();

        return view('Item.edit',compact('item','inv_data','stock_data','uom_data'));
    }

    public function update(Request $req)
    {
        $req->validate([
            'expense_type' => 'required|in:CAPEX,OPEX',
            'capex_code' => 'required_if:expense_type,CAPEX|max:50',
        ]);

        Items::where('id',$req->iid)->update([
            'stock_type'  => $req->stock_type,
            'inv_code'    => $req->inv_code,
            'description' => $req->desc,
            'oem_id'      => $req->oem_id,
            'uom'         => $req->uom,
            'expense_type'=> $req->expense_type,
            'capex_code'  => $req->expense_type === 'CAPEX' ? $req->capex_code : null,
            'serial_no'   => $req->serial,
            'other_specs' => $req->other_specs,
            'cost'        => $req->cost,
            'po_no'       => $req->po,
            'dr_no'       => $req->dr_no,
            'invoice_no'  => $req->invc_no
        ]);

        return back()->with('success','Item successfully updated!');
    }

    // public function save(Request $request){

    //     $validated = $this->validate_data($request);

    //     if($validated['result']){

    //         $nxtid = Items::get_category_series($request->optionsRadios);            

    //         $description = $request->desc;      

    //         $ict = 0;
    //         if($request->isict){
    //             $ict = 1;
    //         }

    //         $size = '';
    //         $color = '';

    //         if($request->optionsRadios==1){
    //             $type=$request->descppe;
    //             $description=$request->descppe;      
    //             $serial=$request->ser;

    //             if($type=='SAFETY SHOES' || $type=='RUBBER BOOTS'){ 
    //                 $size=$request->asize;
    //                 $color=$request->acolor;
    //             }
    //             elseif($type=='RAINCOAT' || $type=='GLOVES'){   
    //                 $size=$request->bsize;
    //                 $color=$request->bcolor;
    //             }
    //             elseif($type=='HARD HAT'){                  
    //                 $color=$request->ccolor;
    //             }
    //             elseif($type=='GOOGLES' || $type=='SPECTACLES'){    
    //                 $color=$request->dcolor;
    //             }
    //         }


    //         if($request->qty > 1){

    //             for($n = 1; $n <= $request->qty; $n++){

    //                 $no_serial = 0;
    //                 $serial = $request->input('ser'.$n);
    //                 if($request->input('noserial'.$n)){
    //                     $no_serial = 1;
    //                     $serial = '';
    //                 }

    //                 if($n > 1){
    //                     $nxtid++;
    //                 }

    //                 $this->store([
    //                     $description, 
    //                     $ict, 
    //                     $request->uom,
    //                     $request->details,
    //                     $request->price,
    //                     $serial,
    //                     $request->pod,
    //                     $request->bisd,
    //                     $request->invoiced,
    //                     $request->rqd,
    //                     $request->optionsRadios,
    //                     $nxtid,
    //                     $color,
    //                     $size,
    //                     $no_serial,
    //                     $request->location
    //                 ]);

    //             }
    //         }

    //         else{

    //             $no_serial = 0;
    //             $serial = $request->input('ser');
    //             if($request->input('noserial')){
    //                 $no_serial = 1;
    //                 $serial = '';
    //             }

    //             $this->store([
    //                 $description, 
    //                 $ict, 
    //                 $request->uom,
    //                 $request->details,
    //                 $request->price,
    //                 $serial,
    //                 $request->pod,
    //                 $request->bisd,
    //                 $request->invoiced,
    //                 $request->rqd,
    //                 $request->optionsRadios,
    //                 $nxtid,
    //                 $color,
    //                 $size,
    //                 $no_serial,
    //                 $request->location
    //             ]);
    //         }
            
    //     }
    //     else{
    //         return $validated;
    //     }
    // }

    // public function store($r){
    //     $save = Items::create([
    //         'name' => $r[0],
    //         'isict' => $r[1],
    //         'uom' => $r[2],
    //         'details' => $r[3],
    //         'qty' => 1,
    //         'price' => $r[4],
    //         'serialNo' => $r[5],
    //         'parentId' => 0,
    //         'tracking' => '',
    //         'po' => $r[6],
    //         'bis' => $r[7],
    //         'invoice' => $r[8],
    //         'rq' => $r[9],
    //         'pldr' => '',
    //         'img' => '',
    //         'unpostRequest' => 0,
    //         'oldpar' => 0,
    //         'is_deleted' => 0,
    //         'd_history' => '',
    //         'd_reason' => '',
    //         'category' => $r[10],
    //         'categorySeries' => $r[11],
    //         'addedBy' => 'test',
    //         'color' => $r[12],
    //         'size' => $r[13],
    //         'noserial' => $r[14],
    //         'location' => $r[15],
    //         'accountabilityHeader_id' => '0',
    //         'is_posted' => '0',
    //     ]);

    //     $update = Items::findOrFail($save->id)->update([
    //         'tracking' => Items::itemrefcode($save->category,$save->categorySeries,$save->created_at)
    //     ]);

    // }

    // public function validate_data($request){

    //     $size="";
    //     $color="";
    //     $n=1;
    //     $description=$request->desc;
    //     $serial=$request->ser;
        
    //     $error_counter=0;
    //     $errors_msg="";     
    //     if(!$request->uom){$errors_msg.="<br>UoM is required,.";$error_counter++;}
    //     if(!$request->qty){$errors_msg.="<br>Qty is required,";$error_counter++;}
    //     if(!$request->price){$errors_msg.="<br>Cost is required,";$error_counter++;}
    //     if($request->qty==0){$errors_msg.="<br>Qty should be greater than 0,";$error_counter++;}
    //     if($request->price<1){$errors_msg.="<br>Cost should be greater than 0,";$error_counter++;}
    //     if(!isset($request->optionsRadios)){$errors_msg.="<br>Category is required,";$error_counter++;}
    //     if(!isset($request->location)){$errors_msg.="<br>Item Location is required,";$error_counter++;}
    //     else{
    //         if($request->optionsRadios==1){
    //             $type=$request->descppe;
    //             $description=$request->descppe;
    //             if(strlen($request->desc)>=1){
    //                 $description=$request->desc;
    //             }               
    //             $serial=$request->ser;
    //             if($type=='SAFETY SHOES' || $type=='RUBBER BOOTS'){ 
    //                 if($request->asize=="0"){$errors_msg.="<br>".$type." size is required,";$error_counter++;}
    //                 $size=$request->asize;
    //                 $color=$request->acolor;
    //             }
    //             elseif($type=='RAINCOAT' || $type=='GLOVES'){   
    //                 if($request->bsize=="0"){$errors_msg.="<br>".$type." size is required,";$error_counter++;}
    //                 $size=$request->bsize;
    //                 $color=$request->bcolor;
    //             }
    //             elseif($type=='HARD HAT'){  
    //                 if($request->ccolor=="0"){$errors_msg.="<br>".$type." color is required,";$error_counter++;}                 
    //                 $color=$request->ccolor;
    //             }
    //             elseif($type=='GOOGLES' || $type=='SPECTACLES'){    
    //                 if($request->dcolor=="0"){$errors_msg.="<br>".$type." color is required,";$error_counter++;}
    //                 $color=$request->dcolor;
    //             }
    //             elseif($type=='MOTORCYCLE HELMET' || 'REFLECTORIZED VEST'){ 
                    
    //             }               
    //             else{
    //                 $errors_msg.=$request->desc."<br>PPE Type is required,";$error_counter++;    
    //             }
    //         }
    //         if($request->optionsRadios==2 || $request->optionsRadios==3){
    //             if(!$request->desc){$errors_msg.="<br>Item Description is required,";$error_counter++;}
    //             if($request->qty>1){
                    
    //                 for($c=1;$c<=$request->qty;$c++){
    //                     if(!$request->input('noserial'.$c)){
    //                         if(!$request->input('ser'.$c)){
    //                             $errors_msg.="<br>You need to enter Serial# for each item. If item has no serial, then please check the No Serial option,";
    //                             $error_counter++;
    //                         }
    //                     }
    //                 }
                    
    //             }
    //             else{
    //                 if(!isset($request->noserial)){
    //                         if(!$request->ser){
    //                             $errors_msg.="<br>You need to enter Serial# for each item. If item has no serial, then please check the No Serial option,";
    //                             $error_counter++;
    //                         }
    //                     }
    //             }
    //         }
    //         if($request->optionsRadios==4){

    //             if(!$request->desc){$errors_msg.="<br>Item Description is required,";$error_counter++;}
                
    //         }
    //     }

    //     if($error_counter>0){
    //         $rs['remarks']="<strong>(".$error_counter.") Error/s found!</strong> Some information of your item is missing ".$errors_msg;
    //         $rs['result'] = false;
    //     }
    //     else{
    //         $rs['remarks'] = 'Success';
    //         $rs['result'] = true;
    //     }

    //     return $rs;
    // }
}
