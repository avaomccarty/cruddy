<x-layout>
    <form action="/name" method="POST">
        <h1 class="is-size-1">Create</h1>
        @if(isset($errors) && count($errors) > 0)
            @foreach($errors as $error)
                <p class="is-danger">{{ $error }}</p>
            @endforeach
        @endif
        @csrf
        <input type="text" name="name-string"  class="input my-2" placeholder="name-string">

				<input type="number" name="name-integer"  class="input my-2">

				<input type="number" name="name-bigInteger"  class="input my-2">

				<input type="submit" value="{{ $name->submit }}" class="button is-primary my-2">

        <a href="/name" class="button is-danger my-2">Cancel</a>
    </form>
</x-layout>
