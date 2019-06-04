@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Audit Log
        </h1>
    </section>
    <div class="content">
        @include('adminlte-templates::common.errors')
        <div class="box box-primary">

            <div class="box-body">
                <div class="row">
                    {!! Form::open(['route' => 'dashboard.auditLogs.store']) !!}

                        @include('dashboard.audit_logs.fields')

                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection
