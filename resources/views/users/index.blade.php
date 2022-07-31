@extends('admin.index')

@section('content')
<div class="row mb-2">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            <h2>Users Management</h2>
        </div>
        <div class="pull-right">
            <a class="btn btn-success" href="{{ route('admin.users.create') }}"> Create New User</a>
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
    <th scope="col">#</th>
    <th scope="col">Name</th>
    <th scope="col">Email</th>
    <th scope="col">Role</th>
    <th scope="col">Permission</th>
    <th scope="col">Created At</th>
    <th width="280px">Action</th>
 </tr>
 @foreach ($users as $key => $user)
  <tr>
    <td>{{ $key }}</td>
    <td>{{ $user->name }}</td>
    <td>{{ $user->email }}</td>
    <td class=text-center>
      @if(!empty($user->getRoleNames()))
        @foreach($user->getRoleNames() as $v)
            <span class="badge badge-success">{{ $v }}</span>
        @endforeach
      @endif
    </td>
    <td class=text-center>
        @if(!empty($user->getAllPermissions()))
            @foreach ($user->getAllPermissions() as $perm)
                <b>{{ $perm->name ?? '' }}</b> |
            @endforeach
        @endif
    </td>
    <td>{{ date('d M, Y h:m A', strtotime($user->created_at)) }}</td>
    <td>
       <a class="btn-sm btn btn-info" href="{{ route('admin.users.show',$user->id) }}">Show</a>
        @hasexactroles('admin')
            @can('update')
                    <a class="btn-sm btn btn-primary" href="{{ route('admin.users.edit',$user->id) }}">Edit</a>
            @endcan
            @can('delete')
            <form action="{{ route('admin.users.destroy', $user->id) }}" method="post">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn-sm btn btn-danger">Delete</button>
            </form>
            @endcan
        @endhasallroles
    </td>
  </tr>
 @endforeach
</table>
@endsection
