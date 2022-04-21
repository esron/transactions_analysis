<x-layout>
    <div class="container mt-4">
        <h1 class="text-center">SISTEMA DE ANÁLISE DE TRANSAÇÕES</h1>
        <form method="POST" id="login" action="{{ route('auth.login') }}">
            @csrf
            <div class="row">
                <div class="col-md-12">
                    <div class="mb-3">
                        <div class="input-group">
                            <input class="form-control @error('email') is-invalid @enderror" type="text" name="email" placeholder="Email" id="name" value="">
                            @error('email')
                            <div class="invalid-feedback mt-1 mb-1">{{ $message ?? '' }}</div>
                            @enderror
                        </div>
                        <div class="input-group">
                            <input class="form-control mt-3 @error('password') is-invalid @enderror" type="password" name="password" placeholder="Senha" id="password" value="">
                            @error('password')
                            <div class="invalid-feedback mt-1 mb-1">{{ $message ?? ''}}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <button type="submit" class="btn btn-primary" id="submit">Entrar</button>
                </div>
            </div>
        </form>
    </div>
</x-layout>
