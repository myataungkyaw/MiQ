<table class="table table-responsive" id="companies-table">
    <thead>
        <tr>
            <th>Name</th>
        <th>Address</th>
        <th>Background Image</th>
        <th>Logo</th>
        <th>Log Retention Period</th>
        <th>Queue Prefix</th>
        <th>Note</th>
        <th>Third Party Integration</th>
        <th>License Key</th>
        <th>Last Sync</th>
            <th colspan="3">Action</th>
        </tr>
    </thead>
    <tbody>
    @foreach($companies as $company)
        <tr>
            <td>{!! $company->name !!}</td>
            <td>{!! $company->address !!}</td>
            <td>{!! $company->background_image !!}</td>
            <td>{!! $company->logo !!}</td>
            <td>{!! $company->log_retention_period !!}</td>
            <td>{!! $company->queue_prefix !!}</td>
            <td>{!! $company->note !!}</td>
            <td>{!! $company->third_party_integration !!}</td>
            <td>{!! $company->license_key !!}</td>
            <td>{!! $company->last_sync !!}</td>
            <td>
                {!! Form::open(['route' => ['dashboard.companies.destroy', $company->id], 'method' => 'delete']) !!}
                <div class='btn-group'>
                    <a href="{!! route('dashboard.companies.show', [$company->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-eye-open"></i></a>
                    <a href="{!! route('dashboard.companies.edit', [$company->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-edit"></i></a>
                    {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Are you sure?')"]) !!}
                </div>
                {!! Form::close() !!}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>