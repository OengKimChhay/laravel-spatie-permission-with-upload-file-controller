@extends('admin.index')

@section('content')
<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            <h2>Role Management</h2>
        </div>
        <div class="pull-right">
            @can('create')
            <a class="btn btn-success" href="{{ route('admin.permissions.create') }}"> Create New Role</a>
            @endcan
        </div>
    </div>
</div>


<table class="table table-bordered">
    <thead>
    <tr>
        <th>#</th>
        <th>Name</th>
        <th>Created At</th>
        <th>Action</th>
    </tr>
    </thead>
    <tbody>
        @forelse ($permissions as $perm)
            <tr>
                <td>{{ $perm->id ?? '' }}</td>
                <td>{{ $perm->name ?? '' }}</td>
                <td>{{ date('d M, Y h:m A', strtotime($perm->created_at)) }}</td>
                <td>
                    <a class="btn btn-info" href="{{ route('admin.permissions.show',$perm->id) }}">Show</a>
                    @hasexactroles('admin')
                        @can('update')
                            <a class="btn btn-primary" href="{{ route('admin.permissions.edit',$perm->id) }}">Edit</a>
                        @endcan
                        @can('delete')
                            <form action="{{ route('admin.permissions.destroy', $perm->id) }}" method="post">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-sm btn btn-danger">Delete</button>
                            </form>
                        @endcan
                    @endhasexactroles
                </td>
            </tr>
        @empty
            <tr><td>No roles found.</td></tr>
        @endforelse
    </tbody>
</table>

@endsection
