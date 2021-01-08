@if ( count( $errors ) > 0 )
    <div class="alert alert-danger alert-dismissible fade in" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">×</span>
        </button>
        @foreach ($errors->all() as $error)
            {{ $error }}<br>
        @endforeach

    </div>
@endif

@if ( Session::has('msg') )

    <div class="alert alert-success alert-dismissible fade in" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">×</span>
        </button>
        <h5><i class="icon fa fa-check"></i> {{ Session::get('msg') }}</h5>
    </div>

@endif

@if ( Session::has('error') )

    <div class="alert alert-warning alert-dismissible fade in" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">×</span>
        </button>
        <h5><i class="icon fa fa-check"></i> {{ Session::get('error') }}</h5>
    </div>

@endif