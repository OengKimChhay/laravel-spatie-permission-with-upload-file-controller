@extends('admin.index')

@section('content')
<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            <h2>Role Management</h2>
        </div>
        <div class="pull-right">
            @can('create')
            <a class="btn btn-success" href="{{ route('admin.roles.create') }}"> Create New Role</a>
            @endcan
        </div>
    </div>
</div>


@if ($message = Session::get('success'))
    <div class="alert alert-success">
        <p>{{ $message }}</p>
    </div>
@endif


<table class="table table-bordered">
  <tr>
     <th>No</th>
     <th>Name</th>
    <th>Permission</th>
     <th>Action</th>
  </tr>
    @foreach ($roles as $key => $role)
    <tr>
        <td>{{ $key }}</td>
        <td>{{ $role->name }}</td>
        <td>
            @foreach ($permissions as $permission )
                @if ($permission->role_id == $role->id)
                    <span class="badge badge-success">{{ $permission->name ?? '' }}</span>
                @endif
            @endforeach
        </td>
        <td>
            <a class="btn btn-info" href="{{ route('admin.roles.show',$role->id) }}">Show</a>
            @hasexactroles('admin')
                @can('update')
                    <a class="btn btn-primary" href="{{ route('admin.roles.edit',$role->id) }}">Edit</a>
                @endcan
                @can('delete')
                    <form action="{{ route('admin.roles.destroy', $role->id) }}" method="post">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-sm btn btn-danger">Delete</button>
                    </form>
                @endcan
            @endhasexactroles
        </td>
    </tr>
    @endforeach
</table>

@endsection
