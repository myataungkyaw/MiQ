<!-- Name Field -->
<div class="form-group col-sm-6">
    {!! Form::label('name', 'Name:') !!}
    {!! Form::text('name', null, ['class' => 'form-control']) !!}
</div>

<!-- Address Field -->
<div class="form-group col-sm-12 col-lg-12">
    {!! Form::label('address', 'Address:') !!}
    {!! Form::textarea('address', null, ['class' => 'form-control']) !!}
</div>

<!-- Background Image Field -->
<div class="form-group col-sm-6">
    {!! Form::label('background_image', 'Background Image:') !!}
    {!! Form::file('background_image') !!}
</div>
<div class="clearfix"></div>

<!-- Logo Field -->
<div class="form-group col-sm-6">
    {!! Form::label('logo', 'Logo:') !!}
    {!! Form::file('logo') !!}
</div>
<div class="clearfix"></div>

<!-- Log Retention Period Field -->
<div class="form-group col-sm-6">
    {!! Form::label('log_retention_period', 'Log Retention Period:') !!}
    {!! Form::select('log_retention_period', $logs_retention_period, null, ['class' => 'form-control']) !!}
</div>

<!-- Queue Prefix Field -->
<div class="form-group col-sm-6">
    {!! Form::label('queue_prefix', 'Queue Prefix:') !!}
    {!! Form::text('queue_prefix', null, ['class' => 'form-control']) !!}
</div>

<!-- Note Field -->
<div class="form-group col-sm-12 col-lg-12">
    {!! Form::label('note', 'Note:') !!}
    {!! Form::textarea('note', null, ['class' => 'form-control']) !!}
</div>

<!-- Third Party Integration Field -->
<div class="form-group col-sm-12">
    {!! Form::label('third_party_integration', 'Third Party Integration:') !!}
    {!! Form::number('third_party_integration', null, ['class' => 'form-control']) !!}
</div>

<!-- License Key Field -->
<div class="form-group col-sm-6">
    {!! Form::label('license_key', 'License Key:') !!}
    {!! Form::text('license_key', null, ['class' => 'form-control']) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('dashboard.companies.index') !!}" class="btn btn-default">Cancel</a>
</div>
