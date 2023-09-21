<table class="table">
    <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Name</th>
            <th scope="col">Email</th>
            <th scope="col">Status</th>
            <th scope="col">Transations</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($users as $user)
        <tr>
            <td> {{ $loop->index + 1 }} </td>
            <td> {{ $user->name }} </td>
            <td> {{ $user->email }}</td>
            <td> {{ $user->status ? 'Active' : 'Inactive' }}</td>
            <td>
                <a class="btn btn-primary" data-bs-toggle="collapse" href="#collapseExample{{$loop->index}}" role="button" aria-expanded="false" aria-controls="collapseExample">
                    View Transctions
                </a>
            </td>
        </tr>
        <tr class="collapse" id="collapseExample{{$loop->index}}">
            <td colspan="5">
                <div class="card card-body">
                    @include('transction.list', ['transctions' => $user->transctions])
                </div>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>