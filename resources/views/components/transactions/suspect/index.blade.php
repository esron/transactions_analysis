<x-layout>
    <div class="container mt-4">
        <h1 class="text-center">ANÁLISE DE TRANSAÇÕES SUSPEITAS</h1>
        <form class="row g-3 mt-3">
            <div class="col-md-6">
                <label for="month">Selecione o mês para analisar as transações</label>
                <select class="form-select" aria-label="Default select example">
                    <option selected>Mês</option>
                    <option value="01">Janeiro</option>
                    <option value="02">Fevereiro</option>
                    <option value="03">Março</option>
                    <option value="04">Abril</option>
                    <option value="05">Maio</option>
                    <option value="06">Junho</option>
                    <option value="07">Julho</option>
                    <option value="08">Agosto</option>
                    <option value="09">Setembro</option>
                    <option value="10">Outubro</option>
                    <option value="11">Novembro</option>
                    <option value="12">Dezembro</option>
                </select>
            </div>
            <div class="col-md-6">
                <label for="month">Selecione o ano para analisar as transações</label>
                <select class="form-select" aria-label="Default select example">
                    <option selected>Ano</option>
                    <option value="2021">2021</option>
                    <option value="2022">2022</option>
                </select>
            </div>
            <div class="col-12">
                <button type="submit" class="btn btn-primary" id="submit">Analisar</button>
            </div>
        </form>
    </div>
    @if($transactions->count() === 0)
    <h2 class="mt-5 text-center">Nenhuma transação encontrada</h2>
    @endif
</x-layout>
