@extends('home')
@section('contenido') 
<div class="all-form"> 
    <div class="form-container">
        <div class="container">
            <div class="layout">
                <main class="main-content">
                    @livewire('documentos-filter')
                </main>
            </div>
        </div>
    </div>
</div>
@endsection