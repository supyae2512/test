@extends('admin.app')
@section('content')

    <script type="text/javascript" src="{{ asset('/js/jquery.1.10.2.js') }}"></script>
    <script src="{{ asset('/js/common.js') }}"></script>
    <script src="{{ asset('/js/jquery-ui.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('/css/jquery-ui.css') }}">


    <!-- page title -->
    <div class="row">
        <div class="col-lg-12">
            <h3 class="page-header"><p class="text-muted"> {{ $title }} </p></h3>
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <!-- -->

    @include('admin.alert')

    @if ( Session::has('cart'))
        <div class="col-lg-8" style="padding: 1%;">
            <div class="col-xs-1">
                <a href="{{ url($route.'/checkout') }}">
                <button class="btn btn-success"><i class="fa fa-adjust"></i>
                    Checkout
                </button>
                </a>
            </div>
        </div>
    @endif



    <!-- /.row body -->
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ $header_title }}
                </div>

                <!-- /.panel-heading -->
                <div class="panel-body">
                    <table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-example">
                        <tbody>
                        @foreach($data as $key => $value)
                            <tr class="odd gradeX">
                                @foreach($column as $col => $attr)

                                    @if($col == "image")
                                        <td><img src="{{ $value->$col }}" style="width:40%;height:20%;">

                                        @else

                                    <td> {{ $value->$col }}</td>

                                    @endif

                                @endforeach
                                <td width="23%">
                                    <a href="{{ url($route.'/add-cart', $value->id) }}"
                                       class="btn btn-sm btn-primary" data-id="{{ $value->id }}"><i class="fa fa-search-plus"></i> Add To Cart </a>
                                </td>
                            </tr>

                        @endforeach

                        </tbody>
                    </table>
                    <!-- /.table-responsive -->
                    <div class="">
                        <div align="center">
                            <?php echo $data->render();?>
                        </div>
                    </div>
                </div>
                <!-- /.panel-body -->
            </div>
            <!-- /.panel -->
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <!-- /.row -->

@endsection