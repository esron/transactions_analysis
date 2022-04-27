<x-layout>
    <div class="container mt-4">
        <h1 class="text-center">IMPORTAR TRANSAÇÕES</h1>
        <div class="row">
            <form method="POST" enctype="multipart/form-data" id="upload-file" action="{{ route('file/upload') }}">
                @csrf
                <div class="row">
                    <div class="col-md-12">
                        <div class="mb-3">
                            <input class="form-control" type="file" name="file" placeholder="Choose file" id="file">
                            <label for="file" class="form-label">Selecionar o arquivo para realizar upload</label>
                            @error('file')
                            <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-primary" id="submit">Importar</button>
                    </div>
                </div>
            </form>
        </div>
        <div class="row mt-3">
        @if (session('message'))
            <div class="alert alert-success">
                {{ session('message') }}
            </div>
        @endif
        </div>
    </div>
    @if (isset($imports) && $imports)
    <div class="container mt-4">
        <h1 class="text-center">IMPORTAÇÕES REALIZADAS</h1>
        <div class="row">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>DATA TRANSAÇÕES</th>
                        <th>DATA IMPORTAÇÃO</th>
                        <th>IMPORTADO POR</th>
                        <th>AÇÕES</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($imports as $import)
                    <tr>
                        <td>{{ $import->transactions_date }}</td>
                        <td>{{ $import->created_at }}</td>
                        <td>{{ $import->user->name }}</td>
                        <td>
                            <a href="{{ route('transactions.index', [$import]) }}" class="btn btn-primary">Detalhes</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="row mt-3">
        </div>
    </div>
    @endif
</x-layout>
