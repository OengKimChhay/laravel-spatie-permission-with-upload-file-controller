@extends('admin.index')


@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="d-flex justify-content-between">
                <h5>Products</h5>
                @can('create')
                    <a class="btn btn-success" href="{{ route('admin.products.create') }}"> Create New Product</a>
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
            <th>Details</th>
            <th>Picture</th>
            <th width="280px">Action</th>
        </tr>
	    @foreach ($products as $product)
	    <tr>
	        <td>{{ ++$i }}</td>
	        <td>{{ $product->name }}</td>
	        <td>{{ $product->detail }}</td>
            <td><img src="{{ asset($product->picture) }}" alt="img" srcset="" style="width:100px;"></td>
	        <td>
                <form action="{{ route('admin.products.destroy',$product->id) }}" method="POST">
                    @csrf
                    @method('DELETE')

                    <a class="btn btn-info" href="{{ route('admin.products.show',$product->id) }}">Show</a>
                    @hasexactroles('admin')
                        @can('update')
                        <a class="btn btn-primary" href="{{ route('admin.products.edit',$product->id) }}">Edit</a>
                        @endcan
                        @can('delete')
                        <button type="submit" class="btn btn-danger">Delete</button>
                        @endcan
                    @endhasexactroles
                </form>
	        </td>
	    </tr>
	    @endforeach
    </table>


    {!! $products->links() !!}

@endsection
