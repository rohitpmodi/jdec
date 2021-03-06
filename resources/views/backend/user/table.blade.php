<table class="table table-responsive table-hover" id="user-table">
    <thead>
    <th width="5%" class="text-center">#</th>
    <th>Parent Name</th>
    <th>Child Name</th>
    <th class="text-center">Type</th>
    <th>Email ID</th>
    <th>Phone</th>
    <th width="10%" class="text-center">Added On</th>
    <th width="10%" class="text-center">Status</th>
    <th width="7%" class="text-center">Action</th>
</thead>
<tbody>
    @foreach($users as $key => $row)
    <tr>
        <td class="text-center">{{ srNo($key) }}</td>
        <td>{!! $row->parent_first_name !!} {!! $row->parent_last_name !!}</td>
        <td>{!! $row->first_name !!} {!! $row->last_name !!}</td>
        <td class="text-center">{!! $row->isAdmin == 1 ? 'Admin' : 'User' !!}</td>
        <td>{{ $row->email }}</td>
        <td>{{ $row->mobile }}</td>
        <td class="text-center">{{ formatDate($row->created_at) }}</td>
        <td class="text-center">{{ getStatus($row->status) }}</td>
        <td class="text-center" nowrap="nowrap">
            {!! Form::open(['route' => ['admin.user.destroy', $row->id], 'method' => 'delete']) !!}
            <div class='btn-group'>
                <a href="{!! route('admin.user.edit', [$row->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-edit" title="Edit"></i></a>
                {!! Form::button('<i class="glyphicon glyphicon-trash" title="Delete"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Are you sure?')"]) !!}
            </div>
            {!! Form::close() !!}
        </td>
    </tr>
    @endforeach
</tbody>
</table>
<div class="col-md-12">
    <ul class="pagination">
        {!! $users->render() !!}
    </ul>
</div>
{{-- $row->links() --}}