@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Line
        </h1>
   </section>
   <div class="content">
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($line, ['route' => ['dashboard.lines.update', $line->id], 'method' => 'patch']) !!}

                        @include('dashboard.lines.fields')

                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
@endsection