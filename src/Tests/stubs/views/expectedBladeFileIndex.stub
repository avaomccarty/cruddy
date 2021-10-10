<x-layout>
    <div class="container">
        <div class="row">
        @if ($names->count())
            <a class="button is-primary m-2" href="/names/create">Create</a>
            <table class="table is-striped is-narrow">
                <thead>
                    <tr>
                        @foreach ($names->first()->toArray() as $key => $value)
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
                    @foreach ($names as $name)
                        <tr>
                            @foreach ($name->toArray() as $value)
                                <td>{{ $value }}</td>
                            @endforeach
                            <td>
                                <a class="button is-primary" href="/names/{{ $name->id }}">Show</a>
                            </td>
                            <td>
                                <a class="button is-info" href="/names/{{ $name->id }}/edit">Edit</a>
                            </td>
                            <td>
                                <form action="/names/{{ $name->id }}" method="POST">
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
                <a class="button is-primary m-2" href="/names/create">Create</a>
            </div>
        @endif
        </div>
        <div class="row">
            <div class="col">
                {{ $names->links() }}
            </div>
        </div>
    </div>
</x-layout>
