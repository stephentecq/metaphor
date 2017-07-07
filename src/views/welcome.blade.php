@extends('layouts.admin')

@section('content')
        <section>

            <div class="">
                @if (Route::has('login'))
                    <div class="top-right links">
                        @if (Auth::check())
                            <a href="{{ url('/home') }}">Home</a>
                        @else
                            <a href="{{ url('/login') }}">Login</a>
                            <a href="{{ url('/register') }}">Register</a>
                        @endif
                    </div>
                @endif

                <div class="content">

                </div>




                <div class="container">


                    @include('Metaphor::forms.upload')


                    <br>
                    <br>
                    <br>
                    <br>
                    <br>



                    <div class="container">
                        <h2>Hover Rows</h2>
                        <p>The .table-hover class enables a hover state on table rows:</p>
                        <table class="table table-hover">
                            <thead>
                            <tr>
                                <th>Firstname</th>
                                <th>Lastname</th>
                                <th>Email</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($data as $lead)
                                <tr>
                                    <td>{{$lead['patient']['first_name']}}</td>
                                    <td>{{$lead['patient']['last_name']}}</td>
                                    <td>{{$lead['patient']['email_1']}}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>

                        {{ Form::open(array('url' => '/facebook/csv/loader/toCrm/', 'files' => true)) }}
                        <input type="hidden" name="batch_id" value="{{$batch_id}}">
                        <input type="hidden" name="send" value="true">
                        <button class="btn btn-primary">Send to crm</button>

                        {{ Form::close() }}

                    </div>


                </div>



            </div>

        </section>
        @endsection

