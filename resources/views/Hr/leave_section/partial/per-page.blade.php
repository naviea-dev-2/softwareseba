<select name="per_page">
    @foreach ([10, 25, 50, 100] as $n)
        <option value="{{ $n }}" @selected((int) request('per_page', 10) === $n)>{{ $n }} rows</option>
    @endforeach
</select>
