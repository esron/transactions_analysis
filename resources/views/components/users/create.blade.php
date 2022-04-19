<x-layout>
    <div class="container mt-4">
        <h1 class="text-center">CADASTRAR NOVO USUÁRIO</h1>
        <form method="POST" id="user-create" action="{{ route('users.store') }}">
            @csrf
            <div class="row">
                <div class="col-md-12">
                    <div class="mb-3">
                        <div class="input-group">
                            <input class="form-control @error('name') is-invalid @enderror" type="text" name="name" placeholder="Nome" id="name" value="{{ old('name') }}">
                            @error('name')
                            <div class="invalid-feedback mt-1 mb-1">{{ $message ?? '' }}</div>
                            @enderror
                        </div>
                        <div class="input-group">
                            <input class="form-control mt-3 @error('email') is-invalid @enderror" type="text" name="email" placeholder="E-mail" id="email" value="{{ old('email') }}">
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
