@foreach($rows as $groupDate => $groupRows)
    <h2>{{ $groupDate }}</h2>
    <table>
        @foreach($groupRows as $row)
            <tr>
                <td>{{ $row['id'] }}</td>
                <td>{{ $row['name'] }}</td>
            </tr>
        @endforeach
    </table>
    <a href="{{ route('upload') }}">Загрузить ещё</a>
@endforeach
