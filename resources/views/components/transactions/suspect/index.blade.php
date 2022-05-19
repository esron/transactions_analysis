<x-layout>
    <div class="container mt-4">
        <h1 class="text-center">ANÁLISE DE TRANSAÇÕES SUSPEITAS</h1>
        <form>
            <label for="month">Selecione o mês para analisar as transações</label>
            <input id="month" type="text" value="" class="form-control">
        </form>

    </div>
    @if($transactions->count() === 0)
        <h2 class="mt-5 text-center">Nenhuma transação encontrada</h2>
    @endif
</x-layout>
