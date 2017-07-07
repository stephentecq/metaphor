@extends('layouts.admin')


@section('content')


    <style>

        .btn-file {
            position: relative;
            overflow: hidden;
        }
        .btn-file input[type=file] {
            position: absolute;
            top: 0;
            right: 0;
            min-width: 100%;
            min-height: 100%;
            font-size: 100px;
            text-align: right;
            filter: alpha(opacity=0);
            opacity: 0;
            outline: none;
            background: white;
            cursor: inherit;
            display: block;
        }

        .panel-collapse-clickable {
            cursor: pointer;
        }

        .panel-default > .panel-heading {
            color: #333;
            background-color: transparent;
            border-color: transparent;
        }

        .panel-default {
            border-color: transparent;
        }
        .panel {
            margin-bottom: 5px;
            background-color: #fff;
            border: 0px solid transparent;
            border-radius: 4px;
            -webkit-box-shadow: 0 1px 1px rgba(0,0,0,.05);
            box-shadow: 0 0px 0px rgba(0,0,0,.05);
        }

    </style>


        <section>
            <div class="container" style="margin-top: 20px;">

                <ul class="nav nav-tabs">
                    <li class="active"><a data-toggle="tab" href="#home">Recent Extractions</a></li>
                    <li><a data-toggle="tab" href="#menu1">Upload & Map</a></li>
                    <li><a data-toggle="tab" href="#menu2">Upload & Extract</a></li>
                </ul>

                <div class="tab-content">
                    <div id="home" class="tab-pane fade in active">
                        <h3>Recent Extractions</h3>
                        <table class="table table-hover">
                            <thead>
                            <tr>
                                <th>File name</th>
                                <th>Date Created</th>
                            </tr>
                            </thead>
                        </table>


                        @forelse($batch as $upload)
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title">

                                    <table class="table">
                                        
                                        <tbody>
                                        <tr>
                                            <td>
                                                <a data-toggle="collapse" data-parent="#accordion-{{ $upload->id }}" href="#filterPanel-{{ $upload->id }}">
                                                    {{ $upload->file_name  }}
                                                </a>
                                            </td>
                                            <td>
                                                <span>
                                                    {{ $upload->created_at  }}
                                                </span>

                                                <span class="pull-right panel-collapse-clickable-{{ $upload->id }}" data-toggle="collapse" data-parent="#accordion-{{ $upload->id }}" href="#filterPanel-{{ $upload->id }}">
                                                    <i class="glyphicon glyphicon-chevron-down"></i>
                                                </span>
                                            </td>

                                        </tr>

                                        </tbody>
                                    </table>

                                </h4>
                            </div>
                            <div id="filterPanel-{{ $upload->id }}" class="panel-collapse-{{ $upload->id }} panel-collapse collapse">
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-xs-8">




                                        <?php

                                        echo'<pre>';
                                            var_export($upload->raw, false);
                                        echo'</pre>';
                                        ?>

                                        </div>
                                        <div class="col-xs-4">
                                            <pre>
                                                <h4>Test</h4>
                                            </pre>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                            <script>

                                $(".panel-collapse-{{ $upload->id }}").on("hide.bs.collapse", function () {
                                    $(".panel-collapse-clickable-{{ $upload->id }}").find('i').removeClass("glyphicon-chevron-up").addClass("glyphicon-chevron-down");
                                });

                                $(".panel-collapse-{{ $upload->id }}").on("show.bs.collapse", function () {
                                    $(".panel-collapse-clickable-{{ $upload->id }}").find('i').removeClass("glyphicon-chevron-down").addClass("glyphicon-chevron-up");
                                });

                            </script>
                            @empty
                            <h4>There are no uploads</h4>
                        @endforelse
                    </div>
                    <div id="menu1" class="tab-pane fade">
                        <h3>Upload and map</h3>
                        @include('Metaphor::forms.upload')
                    </div>
                    <div id="menu2" class="tab-pane fade">
                        <h3>Upload and extract</h3>
                        @include('Metaphor::forms.upload')
                    </div>
                </div>









                {{ $batch->links() }}












               <!-- <div class="row">
                    <div class="col-md-4">
                        <div class="list-group">
                            <a href="#" class="list-group-item disabled">
                                Cras justo odio
                            </a>
                            <a href="#" class="list-group-item">Dapibus ac facilisis in</a>
                            <a href="#" class="list-group-item">Morbi leo risus</a>
                            <a href="#" class="list-group-item">Porta ac consectetur ac</a>
                            <a href="#" class="list-group-item">Vestibulum at eros</a>
                        </div>
                    </div>
                    <div class="col-md-8">

                    </div>
                </div> -->
            </div>





        </section>






        @endsection

