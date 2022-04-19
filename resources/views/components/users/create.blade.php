<x-layout>
    <div class="container mt-4">
        <h1 class="text-center">CADASTRAR NOVO USUÁRIO</h1>
        <form method="POST" id="user-create" action="{{ route('users.store') }}">
            @csrf
            <div class="row">
                <div class="col-md-12">
                    <div class="mb-3">
                        <input class="form-control" type="text" name="name" placeholder="Nome" id="name">
                        <input class="form-control mt-3" type="text" name="email" placeholder="E-mail" id="email">
                        @error('email')
                        <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-12">
                    <button type="submit" class="btn btn-primary" id="submit">Cadastrar</button>
                </div>
            </div>
        </form>
    </div>
</x-layout>
