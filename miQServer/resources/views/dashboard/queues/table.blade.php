<table class="table table-responsive" id="queues-table">
    <thead>
        <tr>
            <th>Company Id</th>
        <th>Name</th>
        <th>Phone</th>
        <th>Third Party Code</th>
        <th>Status</th>
            <th colspan="3">Action</th>
        </tr>
    </thead>
    <tbody>
    @foreach($queues as $queue)
        <tr>
            <td>{!! $queue->company_id !!}</td>
            <td>{!! $queue->name !!}</td>
            <td>{!! $queue->phone !!}</td>
            <td>{!! $queue->third_party_code !!}</td>
            <td>{!! $queue->status !!}</td>
            <td>
                {!! Form::open(['route' => ['dashboard.queues.destroy', $queue->id], 'method' => 'delete']) !!}
                <div class='btn-group'>
                    <a href="{!! route('dashboard.queues.show', [$queue->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-eye-open"></i></a>
                    <a href="{!! route('dashboard.queues.edit', [$queue->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-edit"></i></a>
                    {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Are you sure?')"]) !!}
                </div>
                {!! Form::close() !!}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>