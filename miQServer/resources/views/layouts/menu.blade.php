<li class="{{ Request::is('auditLogs*') ? 'active' : '' }}">
    <a href="{!! route('dashboard.auditLogs.index') !!}"><i class="fa fa-edit"></i><span>Audit Logs</span></a>
</li>

<li class="{{ Request::is('users*') ? 'active' : '' }}">
    <a href="{!! route('dashboard.users.index') !!}"><i class="fa fa-edit"></i><span>Users</span></a>
</li>

<li class="{{ Request::is('lines*') ? 'active' : '' }}">
    <a href="{!! route('dashboard.lines.index') !!}"><i class="fa fa-edit"></i><span>Lines</span></a>
</li>


<li class="{{ Request::is('companies*') ? 'active' : '' }}">
    <a href="{!! route('dashboard.companies.index') !!}"><i class="fa fa-edit"></i><span>Companies</span></a>
</li>

<li class="{{ Request::is('queues*') ? 'active' : '' }}">
    <a href="{!! route('dashboard.queues.index') !!}"><i class="fa fa-edit"></i><span>Queues</span></a>
</li>

<li class="{{ Request::is('dashboard/tags*') ? 'active' : '' }}">
    <a href="{!! route('dashboard.tags.index') !!}"><i class="fa fa-edit"></i><span>Tags</span></a>
</li>

<li class="{{ Request::is('dashboard/printers*') ? 'active' : '' }}">
    <a href="{!! route('dashboard.printers.index') !!}"><i class="fa fa-edit"></i><span>Printers</span></a>
</li>

