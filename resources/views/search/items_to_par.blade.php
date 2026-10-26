<div class="table-responsive">
	<table class="table table-sm table-striped mg-t-4 par-search-table">
		<tbody>
			@forelse($items as $item)
				@php
					$desc = str_replace(array("'",'"'),'`',$item->description);
					$purchaseDate = $item->accountability_created_at ?? null;
					$ageInDays = $purchaseDate ? \Carbon\Carbon::parse($purchaseDate)->startOfDay()->diffInDays(\Carbon\Carbon::today()->startOfDay()) : 0;
					$calculatedBookValue = max(0, (float) $item->cost - (($ageInDays / 365) * ((float) $item->cost / 5)));
				@endphp
				
				@if($type == 'transfer')
					@if(\App\Items::checkSerial($item->id) == 0)
						<tr class="tx-12" id="id{{ $item->id }}">
							<td class="wd-10p">{{ $item->id }}</td>
							<td class="wd-10p">{{ $item->stock_code }}</td>
							<td class="wd-30p">{{ $item->description }}</td>
							<td class="wd-20p">{{ $item->serial_no }}</td>
							<td class="wd-10p">{{ $item->qty }}</td>
							<td class="wd-10p">{{ $item->uom }}</td>
							<td class="wd-10p transfer-cost" data-purchase-date="{{ $purchaseDate }}">{{ number_format($item->cost, 2) }}</td>
							<td class="wd-10p book-value-search-cell" @if($type != 'transfer') style="display:none;" @endif>{{ number_format($calculatedBookValue, 2) }}</td>
							<td class="wd-10p aging-search-cell"></td>
							<td class="wd-10p"><a href="#" class="btn btn-xs btn-primary" onclick='addToItem("{{$item->id}}","{{$item->stock_code}}","{{$desc}}","{{$item->uom}}","{{$item->serial_no}}","{{$item->cost}}","{{$item->qty}}");' role="button">Add</a></td>
						</tr>
					@endif
				@else
					@if($item->serial_no != '')
						@if($item->stock_type == 'DP')
							@if(\App\Items::checkSerial($item->id) == 1)
								<tr class="tx-12" id="id{{ $item->id }}">
									<td class="wd-10p">{{ $item->id }}</td>
									<td class="wd-10p">{{ $item->stock_code }}</td>
									<td class="wd-30p">{{ $item->description }}</td>
									<td class="wd-20p">{{ $item->serial_no }}</td>
									<td class="wd-10p">{{ $item->qty }}</td>
									<td class="wd-10p">{{ $item->uom }}</td>
			<td class="wd-10p transfer-cost" data-purchase-date="{{ $purchaseDate }}">{{ number_format($item->cost, 2) }}</td>
							<td class="wd-10p book-value-search-cell" @if($type != 'transfer') style="display:none;" @endif>{{ number_format($calculatedBookValue, 2) }}</td>
									<td class="wd-10p aging-search-cell"></td>
									<td class="wd-10p"><a href="#" class="btn btn-xs btn-primary" onclick='addToItem("{{$item->id}}","{{$item->stock_code}}","{{$desc}}","{{$item->uom}}","{{$item->serial_no}}","{{$item->cost}}","{{$item->qty}}");' role="button">Add</a></td>
								</tr>
							@endif
						@else
							<tr class="tx-12" id="id{{ $item->id }}">
								<td class="wd-10p">{{ $item->id }}</td>
								<td class="wd-10p">{{ $item->stock_code }}</td>
								<td class="wd-30p">{{ $item->description }}</td>
								<td class="wd-20p">{{ $item->serial_no }}</td>
								<td class="wd-10p">{{ $item->qty }}</td>
								<td class="wd-10p">{{ $item->uom }}</td>
			<td class="wd-10p transfer-cost" data-purchase-date="{{ $purchaseDate }}">{{ number_format($item->cost, 2) }}</td>
							<td class="wd-10p book-value-search-cell" @if($type != 'transfer') style="display:none;" @endif>{{ number_format($calculatedBookValue, 2) }}</td>
								<td class="wd-10p aging-search-cell"></td>
								<td class="wd-10p"><a href="#" class="btn btn-xs btn-primary" onclick='addToItem("{{$item->id}}","{{$item->stock_code}}","{{$desc}}","{{$item->uom}}","{{$item->serial_no}}","{{$item->cost}}","{{$item->qty}}");' role="button">Add</a></td>
							</tr>
						@endif
					@else
						<tr class="tx-12" id="id{{ $item->id }}">
							<td class="wd-10p">{{ $item->id }}</td>
							<td class="wd-10p">{{ $item->stock_code }}</td>
							<td class="wd-30p">{{ $item->description }}</td>
							<td class="wd-20p">{{ $item->serial_no }}</td>
							<td class="wd-10p">{{ $item->qty }}</td>
							<td class="wd-10p">{{ $item->uom }}</td>
							<td class="wd-10p transfer-cost" data-purchase-date="{{ $purchaseDate }}">{{ number_format($item->cost, 2) }}</td>
							<td class="wd-10p book-value-search-cell" @if($type != 'transfer') style="display:none;" @endif>{{ number_format($calculatedBookValue, 2) }}</td>
							<td class="wd-10p aging-search-cell"></td>
							<td class="wd-10p"><a href="#" class="btn btn-xs btn-primary" onclick='addToItem("{{$item->id}}","{{$item->stock_code}}","{{$desc}}","{{$item->uom}}","{{$item->serial_no}}","{{$item->cost}}","{{$item->qty}}");' role="button">Add</a></td>
						</tr>
					@endif
				@endif
				
			@empty
				<tr><td colspan="5"><center><span class="badge badge-info">Item not found...</span></center></td></tr>
			@endforelse
		</tbody>
	</table>
</div>



{{-- <div class="table-responsive">
	<table class="table table-sm table-striped mg-t-4">
		<tbody>
			@forelse($items as $item)
				@php
					$desc = str_replace(array("'",'"'),'`',$item->description);
				@endphp
				
				@if($type == 'transfer')
					@if(\App\Items::checkSerial($item->id) == 0)
						<tr class="tx-12" id="id{{ $item->id }}">
							<td class="wd-10p">{{ $item->id }}</td>
							<td class="wd-10p">{{ $item->stock_code }}</td>
							<td class="wd-30p">{{ $item->description }}</td>
							<td class="wd-20p">{{ $item->serial_no }}</td>
							<td class="wd-10p">{{ $item->qty }}</td>
							<td class="wd-10p">{{ $item->uom }}</td>
			<td class="wd-10p transfer-cost" data-purchase-date="{{ $purchaseDate }}">{{ number_format($item->cost, 2) }}</td>
							<td class="wd-10p book-value-search-cell" @if($type != 'transfer') style="display:none;" @endif>{{ number_format($calculatedBookValue, 2) }}</td>
							<td class="wd-10p aging-search-cell"></td>
							<td class="wd-10p"><a href="#" class="btn btn-xs btn-primary" onclick='addToItem("{{$item->id}}","{{$item->stock_code}}","{{$desc}}","{{$item->uom}}","{{$item->serial_no}}","{{$item->cost}}","{{$item->qty}}");' role="button">Add</a></td>
						</tr>
					@endif
				@else
					@if($item->serial_no != '' )
						@if(\App\Items::checkSerial($item->id) == 1)
							<tr class="tx-12" id="id{{ $item->id }}">
								<td class="wd-10p">{{ $item->id }}</td>
								<td class="wd-10p">{{ $item->stock_code }}</td>
								<td class="wd-30p">{{ $item->description }}</td>
								<td class="wd-20p">{{ $item->serial_no }}</td>
								<td class="wd-10p">{{ $item->qty }}</td>
								<td class="wd-10p">{{ $item->uom }}</td>
							<td class="wd-10p transfer-cost" data-purchase-date="{{ $purchaseDate }}">{{ number_format($item->cost, 2) }}</td>
							<td class="wd-10p book-value-search-cell" @if($type != 'transfer') style="display:none;" @endif>{{ number_format($calculatedBookValue, 2) }}</td>
									<td class="wd-10p aging-search-cell"></td>
								<td class="wd-10p"><a href="#" class="btn btn-xs btn-primary" onclick='addToItem("{{$item->id}}","{{$item->stock_code}}","{{$desc}}","{{$item->uom}}","{{$item->serial_no}}","{{$item->cost}}","{{$item->qty}}");' role="button">Add</a></td>
							</tr>
						@endif
					@else
						<tr class="tx-12" id="id{{ $item->id }}">
							<td class="wd-10p">{{ $item->id }}</td>
							<td class="wd-10p">{{ $item->stock_code }}</td>
							<td class="wd-30p">{{ $item->description }}</td>
							<td class="wd-20p">{{ $item->serial_no }}</td>
							<td class="wd-10p">{{ $item->qty }}</td>
							<td class="wd-10p">{{ $item->uom }}</td>
			<td class="wd-10p transfer-cost" data-purchase-date="{{ $purchaseDate }}">{{ number_format($item->cost, 2) }}</td>
							<td class="wd-10p book-value-search-cell" @if($type != 'transfer') style="display:none;" @endif>{{ number_format($calculatedBookValue, 2) }}</td>
								<td class="wd-10p aging-search-cell"></td>
							<td class="wd-10p"><a href="#" class="btn btn-xs btn-primary" onclick='addToItem("{{$item->id}}","{{$item->stock_code}}","{{$desc}}","{{$item->uom}}","{{$item->serial_no}}","{{$item->cost}}","{{$item->qty}}");' role="button">Add</a></td>
						</tr>
					@endif
				@endif
				
			@empty
				<tr><td colspan="5"><center><span class="badge badge-info">Item not found...</span></center></td></tr>
			@endforelse
		</tbody>
	</table>
</div> --}}





