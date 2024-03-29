<table class="table table-responsive" id="printers-table">
    <thead>
        <tr>
            <th>Name</th>
        <th>Address</th>
        <th>Status</th>
            <th colspan="3">Action</th>
        </tr>
    </thead>
    <tbody>
    @foreach($printers as $printer)
        <tr>
            <td>{!! $printer->name !!}</td>
            <td>{!! $printer->address !!}</td>
            <td>{!! $printer->status !!}</td>
            <td>
                {!! Form::open(['route' => ['dashboard.printers.destroy', $printer->id], 'method' => 'delete']) !!}
                <div class='btn-group'>
                    <a href="{!! route('dashboard.printers.show', [$printer->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-eye-open"></i></a>
                    <a href="{!! route('dashboard.printers.edit', [$printer->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-edit"></i></a>
                    {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Are you sure?')"]) !!}
                </div>
                {!! Form::close() !!}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>