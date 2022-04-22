<x-layout>
    <div class="container mt-4">
        <h1 class="text-center">DETALHES DA IMPORTAÇÃO</h1>
        <div class="card">
            <div class="card-body">
                <form>
                    <div class="row mb-2">
                        <label for="createdAt" class="col-sm-2 col-form-label">Importado em</label>
                        <div class="col-sm-3">
                            <input id="createdAt"type="text" value="{{ $import->created_at->format('d/m/Y H:i') }}" class="form-control" disabled>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <label for="importedBy" class="col-sm-2 col-form-label">Importado por</label>
                        <div class="col-sm-3">
                            <input id="importedBy" type="text" value="{{ $import->user->name }}" class="form-control" disabled>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <label for="importedAt" class="col-sm-2 col-form-label">Data das transações</label>
                        <div class="col-sm-3">
                            <input id="importedAt" type="text" value="{{ $import->transactions_date->format('d/m/Y') }}" class="form-control" disabled>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <h2 class="text-center mt-4">TRANSAÇÕES IMPORTADAS</h2>
        <table class="table table-bordered table-striped align-middle">
            <thead>
                <tr>
                    <th scope="col" class="text-center" colspan="3">ORIGEM</th>
                    <th scope="col" class="text-center" colspan="3">DESTINO</th>
                    <th scope="col" class="text-center align-middle" rowspan="2">VALOR</th>
                </tr>
                <tr>
                    <th scope="col" class="text-center">BANCO</th>
                    <th scope="col" class="text-center">AGÊNCIA</th>
                    <th scope="col" class="text-center">CONTA</th>
                    <th scope="col" class="text-center">BANCO</th>
                    <th scope="col" class="text-center">AGÊNCIA</th>
                    <th scope="col" class="text-center">CONTA</th>
                </tr>
            </thead>
            <tbody>
                @foreach($import->transactions as $transaction)
                <tr>
                    <td>{{ $transaction->originAccount->bank_name }}</td>
                    <td>{{ $transaction->originAccount->branch }}</td>
                    <td>{{ $transaction->originAccount->number }}</td>
                    <td>{{ $transaction->destinyAccount->bank_name }}</td>
                    <td>{{ $transaction->destinyAccount->branch }}</td>
                    <td>{{ $transaction->destinyAccount->number }}</td>
                    <td class="d-flex flex-row-reverse">R$ {{ $transaction->amount }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-layout>
