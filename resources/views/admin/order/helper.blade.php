@foreach($orders as $order)
	                  	<tr>
		                    <td>{{ $loop->index + 1 }}</td>
		                    <td><a href="{{ route('order.edit', $order->id) }}">{{ $order->code }}</a></td>
                        <td>{{ $order->name }}</td>
                        <td>{{ $order->phone }}</td>
                        
                        <td><span class="badge badge-{{ $order->status->color }}">{{ $order->status->title }}</span></td>
                        <td>{{\Carbon\Carbon::parse($order->created_at)->format('d M, Y g:iA')}}</td>
                        <td>
                          <a href="{{ route('order.invoice.generate', $order->id) }}" class="btn btn-secondary" title="Download Invoice"><i class="fas fa-download"></i></a>
                          <a href="{{ route('order.edit', $order->id) }}" class="btn btn-primary" title="Edit"><i class="fas fa-edit"></i></a>
                          <!-- <a href="#deleteModal{{ $order->id }}" class="btn btn-danger" data-toggle="modal" title="Delete"><i class="fas fa-trash"></i></a> -->
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