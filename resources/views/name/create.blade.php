<x-layout>
    <form action="/table" method="POST">
        <h1 class="is-size-1">Create</h1>
        @if(isset($errors) && count($errors) > 0)
            @foreach($errors as $error)
                <p class="is-danger">{{ $error }}</p>
            @endforeach
        @endif
        @csrf
        /Users/joshmccarty/code/cruddy/src/Commands/stubs/views/default/inputs/text.stub
		/Users/joshmccarty/code/cruddy/src/Commands/stubs/views/default/inputs/number.stub
		/Users/joshmccarty/code/cruddy/src/Commands/stubs/views/default/inputs/submit.stub
        <a href="/table" class="button is-danger my-2">Cancel</a>
    </form>
</x-layout>
