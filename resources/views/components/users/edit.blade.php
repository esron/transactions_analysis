<x-layout>
    <div class="container mt-4">
        <h1 class="text-center">EDITAR USUÁRIO</h1>
        @if(session('message'))
        <div class="alert alert-success">{{ session('message') }}</div>
        @endif
        <form method="POST" id="user-edit" action="{{ route('users.update', [$user]) }}">
            @method('PUT')
            @csrf
            <div class="row">
                <div class="col-md-12">
                    <div class="mb-3">
                        <div class="input-group">
                            <input class="form-control @error('name') is-invalid @enderror" type="text" name="name" placeholder="Nome" id="name" value="{{ $user->name }}">
                            @error('name')
                            <div class="invalid-feedback mt-1 mb-1">{{ $message ?? '' }}</div>
                            @enderror
                        </div>
                        <div class="input-group">
                            <input class="form-control mt-3 @error('email') is-invalid @enderror" type="text" name="email" placeholder="E-mail" id="email" value="{{ $user->email }}">
                            @error('email')
                            <div class="invalid-feedback mt-1 mb-1">{{ $message ?? ''}}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <button type="submit" class="btn btn-primary" id="submit">Cadastrar</button>
                </div>
            </div>
        </form>
    </div>
</x-layout>
