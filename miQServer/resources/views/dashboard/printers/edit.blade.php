@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Printer
        </h1>
   </section>
   <div class="content">
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($printer, ['route' => ['dashboard.printers.update', $printer->id], 'method' => 'patch']) !!}

                        @include('dashboard.printers.fields')

                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
@endsection