@extends('admin.layouts.master')
@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Current Year Orders</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">

                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                        <li class="breadcrumb-item active">order</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
    <section class="content">
        <div class="container-fluid">
            <div class="card">
                @if (!Auth::user()->vendor)
                    <div class="card-header">
                        {{-- Total Orders --}}
                        <h4>Total Orders : {{ count($orders) }} <span style="font-size: 22px">(From POS : {{ count($orders->where('source', 'Offline')) }}, From Website : {{ count($orders->where('source', 'Website')) }}, Wholesale : {{ count($orders->where('source', 'Wholesale')) }})</span></h4>

                        {{-- Total Orders Confirmed --}}
                        <h4 class="mt-4">Total Orders Confirmed : {{ count($orders->where('is_final', 1)->where('order_status_id', '!=', 5)) }} <span style="font-size: 22px">(From POS : {{ count($orders->where('source', 'Offline')->where('is_final', 1)->where('order_status_id', '!=', 5)) }}, From Website : {{ count($orders->where('source', 'Website')->where('is_final', 1)->where('order_status_id', '!=', 5)) }}, Wholesale :
                                {{ count($orders->where('source', 'Wholesale')->where('is_final', 1)->where('order_status_id', '!=', 5)) }})</span>
                        </h4>

                        {{-- Confirmed Orders Amount --}}
                        <h4>Confirmed Orders Amount :
                            {{ round($orders->where('is_final', 1)->where('order_status_id', '!=', 5)->sum('price')) }} TK
                            <span style="font-size: 22px">(From POS : {{ round($orders->where('source', 'Offline')->where('is_final', 1)->where('order_status_id', '!=', 5)->sum('price')) }} TK, From Website : {{ round($orders->where('source', 'Website')->where('is_final', 1)->where('order_status_id', '!=', 5)->sum('price')) }} TK, Wholesale :
                                {{ round($orders->where('source', 'Wholesale')->where('is_final', 1)->where('order_status_id', '!=', 5)->sum('price')) }}
                                TK)</span>
                        </h4>

                        {{-- Total Orders Returned --}}
                        <h5 class="text-" style="color: #e97900">Total Orders Returned : {{ count($orders->where('order_status_id', '!=', 5)->where('is_return', '!=', 0)) }} <span style="font-size: 18px">(Fully: {{ count($orders->where('order_status_id', '!=', 5)->where('is_return', 1)) }}, Partially: {{ count($orders->where('order_status_id', '!=', 5)->where('is_return', 2)) }})</span></h5>

                        {{-- Orders Not Confirmed --}}
                        <h4 class="mt-4">Orders Not Confirmed : {{ count($orders->where('is_final', 0)->where('order_status_id', '!=', 5)) }} <span style="font-size: 22px">(From POS : {{ count($orders->where('source', 'Offline')->where('is_final', 0)->where('order_status_id', '!=', 5)) }}, From Website : {{ count($orders->where('source', 'Website')->where('is_final', 0)->where('order_status_id', '!=', 5)) }}, Wholesale :
                                {{ count($orders->where('source', 'Wholesale')->where('is_final', 0)->where('order_status_id', '!=', 5)) }})</span>
                        </h4>

                        {{-- Not Confirmed Orders Amount --}}
                        <h4>Not Confirmed Orders Amount :
                            {{ $orders->where('is_final', 0)->where('order_status_id', '!=', 5)->sum('price') }} TK <span style="font-size: 22px">(From POS : {{ $orders->where('source', 'Offline')->where('is_final', 0)->where('order_status_id', '!=', 5)->sum('price') }} TK, From Website : {{ $orders->where('source', 'Website')->where('is_final', 0)->where('order_status_id', '!=', 5)->sum('price') }} TK, Wholesale :
                                {{ $orders->where('source', 'Wholesale')->where('is_final', 0)->where('order_status_id', '!=', 5)->sum('price') }}
                                TK)</span>
                        </h4>

                        {{-- Total Orders Cancelled --}}
                        <h5 class="text-danger mt-4">Total Orders Cancelled : {{ count($orders->where('order_status_id', '==', 5)) }}</h5>
                    </div>
                @else
                    <div class="card-header">
                        {{-- Total Orders --}}
                        <h4>Total Orders : {{ count($orders) }}</h4>

                        {{-- Total Orders Confirmed --}}
                        <h4 class="mt-4">Total Orders Confirmed : {{ count($orders->where('is_final', 1)->where('order_status_id', '!=', 5)) }}
                        </h4>

                        {{-- Confirmed Orders Amount --}}
                        <h4>Confirmed Orders Amount :
                            {{ round($orders->where('is_final', 1)->where('order_status_id', '!=', 5)->sum('price')) }} TK

                        </h4>

                        {{-- Total Orders Returned --}}
                        <h5 class="text-" style="color: #e97900">Total Orders Returned : {{ count($orders->where('order_status_id', '!=', 5)->where('is_return', '!=', 0)) }} </h5>

                        {{-- Total Orders Cancelled --}}
                        <h5 class="text-danger mt-4">Total Orders Cancelled : {{ count($orders->where('order_status_id', '==', 5)) }}</h5>
                    </div>
                @endif
                <!-- /.card-header -->
                @include('admin.partials.page_search')
                <div class="card-body table-responsive">
                    <table id="data-table" class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>S.N</th>
                                <th>Code</th>
                                <th>Customer Name</th>
                                <th>Phone</th>
                                <th>Status</th>
                                <th width="20%">Note</th>
                                <th>Source</th>
                                <th>Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($orders as $order)
                                <tr>
                                    <td>{{ $loop->index + 1 }}</td>
                                    <td><a href="{{ route('order.edit', $order->id) }}">{{ $order->code }}</a></td>
                                    <td>{{ $order->name }}</td>
                                    <td>{{ $order->phone }}</td>
                                    <td><span class="badge badge-{{ $order->status->color }}">{{ $order->status->title }}</span></td>
                                    <td>{{ $order->note }}</td>
                                    <td>{{ $order->source }}</td>
                                    <td>{{ \Carbon\Carbon::parse($order->created_at)->format('d M, Y g:iA') }}</td>
                                    <td>
                                        <a href="{{ route('order.invoice.generate', $order->id) }}" class="btn btn-secondary" title="Download Invoice"><i class="fas fa-download"></i></a>
                                        <a href="{{ route('order.edit', $order->id) }}" class="btn btn-primary" title="Edit"><i class="fas fa-edit"></i></a>
                                        <a href="#deleteModal{{ $order->id }}" class="btn btn-danger" data-toggle="modal" title="Delete"><i class="fas fa-trash"></i></a>
                                    </td>
                                </tr>
                                <!-- Delete order Modal -->
                                <div class="modal fade" id="deleteModal{{ $order->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Are tou sure you want to delete ?</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <form action="{{ route('order.destroy', $order->id) }}" method="POST">
                                                    @csrf
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-danger">Permanent Delete</button>
                                                </form>

                                            </div>
                                            <div class="modal-footer">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>S.N</th>
                                <th>Code</th>
                                <th>Customer Name</th>
                                <th>Phone</th>
                                <th>Status</th>
                                <th width="20%">Note</th>
                                <th>Source</th>
                                <th>Date</th>
                                <th>Action</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->


        </div>
    </section>
@endsection

@section('scripts')
    <script>
        $(function() {
            $("#data-table").DataTable({
                "responsive": true,
                "lengthChange": false,
                "autoWidth": false,
                "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
            }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
            $('#example2').DataTable({
                "paging": true,
                "lengthChange": false,
                "searching": true,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "responsive": true,
            });
        });
    </script>
@endsection
