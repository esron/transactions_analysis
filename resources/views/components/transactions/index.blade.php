<x-layout>
    <div class="container mt-4">
        <h1 class="text-center">DETALHES DA IMPORTAÇÃO</h1>
        <div class="card">
            <div class="card-body">
                <form>
                    <div class="row mb-2">
                        <label class="col-sm-2 col-form-label">Importado em</label>
                        <div class="col-sm-3">
                            <input type="text" value="{{ $import->created_at->format('d/m/Y H:i') }}" class="form-control" disabled>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <label class="col-sm-2 col-form-label">Importado por</label>
                        <div class="col-sm-3">
                            <input type="text" value="{{ $import->user->name }}" class="form-control" disabled>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <label class="col-sm-2 col-form-label">Data das transações</label>
                        <div class="col-sm-3">
                            <input type="text" value="{{ $import->transactions_date->format('d/m/Y') }}" class="form-control" disabled>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layout>
