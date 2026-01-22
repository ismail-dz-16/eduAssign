@extends('layouts.app')

@section('content')
<div class="header">
    <h2>Liste des questions</h2>
    <a class="btn" href="{{ route('questions.create') }}">+ Nouvelle Question</a>
</div>

<table>
    <tr>
        <th>Titre</th>
        <th>Type</th>
        <th>Action</th>
    </tr>

    @foreach($questions as $q)
    <tr>
        <td>{{ $q->title }}</td>
        <td>{{ ucfirst(str_replace('_',' ', $q->type)) }}</td>
        <td>
            <form method="POST" action="{{ route('questions.destroy',$q) }}">
                @csrf @method('DELETE')
                <button class="danger">Supprimer</button>
            </form>
        </td>
    </tr>
    @endforeach
</table>
@endsection
