<table class="table table-responsive" id="lines-table">
    <thead>
        <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Color</th>
        <th>Priority</th>
            <th colspan="3">Action</th>
        </tr>
    </thead>
    <tbody>
    @foreach($lines as $line)
        <tr>
            <td>{{ $line->id }}</td>
            <td>{!! $line->name !!}</td>
            <td>{!! $line->color !!}</td>
            <td>{!! $line->priority !!}</td>
            <td>
                {!! Form::open(['route' => ['dashboard.lines.destroy', $line->id], 'method' => 'delete']) !!}
                <div class='btn-group'>
                    <a href="{!! route('dashboard.lines.show', [$line->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-eye-open"></i></a>
                    <a href="{!! route('dashboard.lines.edit', [$line->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-edit"></i></a>
                    {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Are you sure?')"]) !!}
                </div>
                {!! Form::close() !!}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>