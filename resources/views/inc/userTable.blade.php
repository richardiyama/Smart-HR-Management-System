<table id="datatable-buttons" class="table table-striped table-bordered">
    <thead>
        <tr>
            <th>{{__('user_role.table.no')}}</th>
            <th>{{__('user_role.table.name')}}</th>
            <th>{{__('user_role.table.email')}}</th>
            <th>{{__('user_role.table.role')}}</th>
            <th>{{__('user_role.table.action')}}</th>
        </tr>
    </thead>
    <tbody>
        @if(count($users)>0)
            @foreach ($users as $user)
                <tr>
                    <td>{{$loop->iteration}}</td>
                    <td>{{$user['name']}}</td>
                    <td>{{$user['email']}}</td>
                    <td>
                        <?php 
                        $the_role = DB::table('role')->where('id',$user['role'])->first();
                        echo $the_role->role_name;
                        ?>
                        
                        </td>
                    <td>
                        <a href="{{action('UsersController@edit', $user['id'])}}" ><span class="label label-warning"> {{__('user_role.button.edit')}} <i class="fa fa-edit"></i></span></a>
                        <a onclick="return confirm('Are you sure you want to delete this role ? ')" href="{{ route('User.delete', $user['id']) }}"><span class="label label-danger"> {{__('user_role.button.delete')}} <i class="fa fa-trash"></i></span></a>
                    </td>
                </tr>
            @endforeach
        @endif
    </tbody>
</table>