<table class="table table-responsive" id="auditLogs-table">
    <thead>
        <tr>
            <th>Category</th>
        <th>User Id</th>
        <th>Action</th>
            <th colspan="3">Action</th>
        </tr>
    </thead>
    <tbody>
    @foreach($auditLogs as $auditLog)
        <tr>
            <td>{!! $auditLog->category !!}</td>
            <td>{!! $auditLog->user_id !!}</td>
            <td>{!! $auditLog->action !!}</td>
            <td>
                {!! Form::open(['route' => ['dashboard.auditLogs.destroy', $auditLog->id], 'method' => 'delete']) !!}
                <div class='btn-group'>
                    <a href="{!! route('dashboard.auditLogs.show', [$auditLog->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-eye-open"></i></a>
                    <a href="{!! route('dashboard.auditLogs.edit', [$auditLog->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-edit"></i></a>
                    {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Are you sure?')"]) !!}
                </div>
                {!! Form::close() !!}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>