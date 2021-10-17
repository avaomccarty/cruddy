<x-layout>
    <div class="container">
        <div class="row">
        @if (${{ variableCollection }}->count())
            <a class="button is-primary m-2" href="/{{ variableCollection }}/create">Create</a>
            <table class="table is-striped is-narrow">
                <thead>
                    <tr>
                        @foreach (${{ variableCollection }}->first()->toArray() as $key => $value)
                            <th>
                                {{ $key }}
                            </th>
                        @endforeach
                        <th></th>
                        <th></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach (${{ variableCollection }} as ${{ variable }})
                        <tr>
                            @foreach (${{ variable }}->toArray() as $value)
                                <td>{{ $value }}</td>
                            @endforeach
                            <td>
                                <a class="button is-primary" href="/{{ variableCollection }}/{{ ${{ variable }}->id }}">Show</a>
                            </td>
                            <td>
                                <a class="button is-info" href="/{{ variableCollection }}/{{ ${{ variable }}->id }}/edit">Edit</a>
                            </td>
                            <td>
                                <form action="/{{ variableCollection }}/{{ ${{ variable }}->id }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <input type="submit" class="button is-danger" value="Delete">
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div>
                <p class="is-size-3 is-centered">Your query returned zero results.</p>
                <a class="button is-primary m-2" href="/{{ variableCollection }}/create">Create</a>
            </div>
        @endif
        </div>
        <div class="row">
            <div class="col">
                {{ ${{ variableCollection }}->links() }}
            </div>
        </div>
    </div>
</x-layout>
