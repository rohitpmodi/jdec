<table class="table table-responsive table-hover" id="standard-table">
    <thead>
    <th width="5%" class="text-center">#</th>
    <th>Name</th>
    <th>School</th>
    <th>Added On</th>
    <th width="7%" class="text-center">Action</th>
</thead>
<tbody>
    @foreach($standard as $standard)
    <tr>
        <td class="text-center">{{ $standard->id }}</td>
        <td>{{ $standard->name }}</td>
        <td>{{ $standard->school->name }}</td>
        <td>{{ formatDate($standard->created_at) }}</td>
        <td class="text-center" nowrap="nowrap">
            {!! Form::open(['route' => ['admin.standard.destroy', $standard->id], 'method' => 'delete']) !!}
            <div class='btn-group'>
<!--                    <a href="{!! route('admin.standard.show', [$standard->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-eye-open"></i></a>-->
                <a href="{!! route('admin.standard.edit', [$standard->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-edit"></i></a>
                {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Are you sure?')"]) !!}
            </div>
            {!! Form::close() !!}
        </td>
    </tr>
    @endforeach
</tbody>
</table>
{{-- $standard->links() --}}