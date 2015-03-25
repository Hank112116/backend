<div class="panel panel-{!! $perk->is_pro? 'expert' : 'custom' !!}
            {!! $perk->isEditable()? '' : 'dis-editable' !!}">
    
    <input type="hidden" name = 'perks[{!! $perk->perk_id !!}][is_pro]' value = '{!! $perk->is_pro !!}'>

    @if($perk->is_new)
        <input type="hidden" name = 'perks[{!! $perk->perk_id !!}][is_new]' value = '1'>
    @endif
    
    <div class="panel-heading perk-heading">
        <h3 class="panel-title">
        {!! $perk->is_pro? 'EXPERT PERK' : 'CUSTOM PERK' !!} # {!! $perk->is_new? 'NEW' : $perk->perk_id !!}
        </h3>

        @if($perk->isEditable())
        <div class="to-delete">
            <input type='checkbox' name="perks[{!! $perk->perk_id !!}][is_deleted]" value='1'
                   class='js-delete-perk' {!! $perk->is_deleted ? 'checked' : '' !!} />
            <span>DELETE PERK?</span>
        </div>
        @endif

    </div>

    <div class="panel-body perk-edit">
	    <div class="input-group">
            <span class="input-group-addon">Perk amount</span>
            <input class="form-control perk-amount" name='perks[{!! $perk->perk_id !!}][perk_amount]' 
            		value="{!! $perk->perk_amount !!}">
            <span class="input-group-addon">USD</span>
        </div>

	    <div class="input-group">
            <span class="input-group-addon">Title</span>
            <input class="form-control" name='perks[{!! $perk->perk_id !!}][perk_title]' 
            	value="{!! $perk->perk_title !!}">
        </div>

	    <div class="input-group">
            <span class="input-group-addon">Description</span>
            <textarea class="form-control"
                name='perks[{!! $perk->perk_id !!}][perk_description]'
            	rows='5'>{!! $perk->perk_description !!}</textarea>
        </div>

		<div class="clearfix">
		    <div class="input-group group-helf is-ship-fee">
			    <input type='checkbox' name="perks[{!! $perk->perk_id !!}][has_shipping_fee]" value='1' 
			    	{!! $perk->has_shipping_fee? 'checked' : '' !!} 
                    {!! $perk->isEditable()? '' : 'disabled' !!} /> 
			    <span class='is-ship-fee'>Add Shipping Fee?</span>
	        </div>

		    <div class="input-group group-helf">
	            <span class="input-group-addon">To USA</span>
	            <input class="form-control perk-fee-us" name='perks[{!! $perk->perk_id !!}][shipping_fee_us]' 
	            	value="{!! $perk->shipping_fee_us !!}">
	            <span class="input-group-addon">USD</span>
	        </div>

		    <div class="input-group group-helf">
	            <span class="input-group-addon">To Intl</span>
	            <input class="form-control perk-fee-intl" name='perks[{!! $perk->perk_id !!}][shipping_fee_intl]' 
	            	value="{!! $perk->shipping_fee_intl !!}">
	            <span class="input-group-addon">USD</span>
	        </div>			
		</div>

	    <div class="input-group">
            <span class="input-group-addon">Estimate delivery date</span>
            <input class="form-control" name='perks[{!! $perk->perk_id !!}][perk_delivery_date]' 
            	value="{!! $perk->perk_delivery_date !!}">
        </div>

	    <div class="input-group">
            <span class="input-group-addon">Limited quantity</span>
            <input class="form-control" name='perks[{!! $perk->perk_id !!}][perk_total]' 
            	value="{!! $perk->perk_total == -1 ? '' : $perk->perk_total !!}">
        </div>

        <div class='perk-edit-warning'>
            @if(!$perk->isEditable())
                <span>This perk already has baker</span>
            @endif
        </div>

    </div>
</div>