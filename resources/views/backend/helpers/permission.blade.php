@foreach ($roles as $role)
    <tr>
        <td>{{ $loop->iteration }}</td>
        <td>{{ $role->name }}</td>
        <td>
            @foreach ($role->permissions as $permission)
                <span class="fs-15 badge bg-success">{{ $permission->name }}</span>
            @endforeach
        </td>
    </tr>
@endforeach
