
    {{-- Status --}}
    <div class="form-group mb-4 row">
        <label class="col-sm-2 col-form-label" for="day_is_vacation">@lang('main.day_is_vacation')<span class="required"></span> </label>
        <div class="col-sm-10">
            <input type="hidden" name="day_is_vacation" value="no">
            
            <input type="checkbox" id="day_is_vacation" name="day_is_vacation" class="switch"  
                data-on-color="success" 
                data-off-color="info" 
                data-on-text="@lang('main.yes')" 
                data-off-text="@lang('main.no')" 
                value="yes"
                {{ $work_time->day_is_vacation ? 'checked' : '' }}
            >
                
            @error('day_is_vacation')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
    </div>
    
    {{-- Open Time --}}
    <div class="form-group mb-4 row">
        <label class="col-sm-2 col-form-label" for="open_time">@lang('main.open_time')<span class="required"></span> </label>
        <div class="col-sm-10">

            <input type="text" name="open_time" value="{{ $work_time->open_time->format('H:i') }}" id="open_time-{{ $work_time->id }}" class="form-control @error('open_time') is-invalid @enderror" placeholder="@lang('main.open_time')" autocomplete="off">
                
            @error('open_time')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
    </div>
    
    {{-- Close Time --}}
    <div class="form-group mb-4 row">
        <label class="col-sm-2 col-form-label" for="close_time">@lang('main.close_time')<span class="required"></span> </label>
        <div class="col-sm-10">

            <input type="text" name="close_time" value="{{ $work_time->close_time->format('H:i') }}" id="close_time-{{ $work_time->id }}" class="form-control @error('close_time') is-invalid @enderror" placeholder="@lang('main.close_time')" autocomplete="off">
                
            @error('close_time')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
    </div>
    