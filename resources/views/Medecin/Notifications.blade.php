@extends('Medecin.layouts.master')
@section('content')
<div class="container center-div">
    <center><h1>Notifications</h1></center>
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <div class="row">
                @if($errors->any())
                    <div class="alert alert-danger col-12 mt-1 mb-0" id="warningSubmit" role="alert">
                    <center><ul>
                            @foreach($errors->all() as $error)
                            <li>{{$error}}</li>
                            @endforeach
                        <ul></center>
                    </div>
                @endif
                @if(session()->has('success'))
                    <div class="alert alert-success col-12 mt-1 mb-0" style="width:100%">
                           <center> 
                                {{ session()->get('success') }}
                           </center>
                    </div>
                @endif
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th width="90%">Contenu</th>
                            <th width="10%">Supprimer</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($notifs as $notif)
                            <tr>
                                <td width="90%"><pre><?php echo $notif->contenu ?></pre></td>
                                <td width="10%">
                                    <center>
                                        <form action="{{route('medecin.suppNotification')}}" method="post">
                                            <input type="hidden" name="id_notif" value="{{$notif->id_notif}}">
                                            {{ csrf_field() }}
                                            {{ method_field('DELETE') }}
                                            <button type="submit" class="btn">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </center>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection