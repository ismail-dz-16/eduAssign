@extends('layouts.app')

@section('content')
<h2>Créer une question</h2>

<form method="POST" action="{{ route('questions.store') }}" id="questionForm">
    @csrf

    <label>Type</label>
    <select name="type" id="type">
        <option value="">-- Choisir --</option>
        <option value="short_answer">Réponse courte</option>
        <option value="choices">Choix multiples</option>
        <option value="yes_no">Oui / Non</option>
    </select>

    <label>Titre</label>
    <input type="text" name="title" required>

    <label>Description</label>
    <input type="text" name="description">

    <label>Question</label>
    <input type="text" name="question" required>

    <div id="short_answer" class="box">
        <label>Réponse</label>
        <input type="text" name="short_answer">
    </div>

    <div id="choices" class="box">
        <label>Choix</label>
        <input type="text" name="choice_answer[]">
        <input type="text" name="choice_answer[]">
        <input type="text" name="choice_answer[]">
    </div>

    <div id="yes_no" class="box">
        <label>Réponse</label>
        <select name="yes_no_answer">
            <option value="1">Oui</option>
            <option value="0">Non</option>
        </select>
    </div>

    <button class="btn">Enregistrer</button>
</form>
@endsection
