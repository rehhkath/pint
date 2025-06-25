<?php

namespace App\Http\Controllers;


class SegmentController extends Controller
{
    public function getSegments()
    {
        return response()->json([
            'segments' => [
                ['id' => 1, 'name' => 'Pós venda'],
                ['id' => 2, 'name' => 'Cashback expirando'],
                ['id' => 3, 'name' => 'Aniversariante do mês'],
                ['id' => 4, 'name' => 'Aniversariante do dia'],
                ['id' => 5, 'name' => 'Consumidores que não compram há mais de 90 dias'],
            ],
        ]);
    }
}