@php $gradesss = \App\Models\Grade::all(); @endphp
@foreach ($gradesss as $grades)
    <option value="{{ $grades->id }}"
        {{ in_array($grades->id, $grades1) ? 'selected' : '' }}>
        {{ $grades->name }}
    </option>
@endforeach