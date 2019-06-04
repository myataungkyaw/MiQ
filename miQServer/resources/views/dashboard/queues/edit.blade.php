@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Queue
        </h1>
   </section>
   <div class="content">
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($queue, ['route' => ['dashboard.queues.update', $queue->id], 'method' => 'patch']) !!}

                        @include('dashboard.queues.fields')

                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
@endsection