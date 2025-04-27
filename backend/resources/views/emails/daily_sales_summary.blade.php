<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resumo de Vendas Diárias</title>
</head>
<body>
    <h1>Resumo de Vendas - {{ now()->format('d/m/Y') }}</h1>

    <p>Vendas realizadas no dia: {{ $salesCount }}</p>
    <p>Valor total das vendas: R$ {{ number_format($totalSalesValue, 2, ',', '.') }}</p>
    <p>Comissão total: R$ {{ number_format($totalCommission, 2, ',', '.') }}</p>

</body>
</html>
