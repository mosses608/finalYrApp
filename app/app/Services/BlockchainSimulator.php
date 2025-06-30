<?php
namespace App\Services;

class BlockchainSimulator
{
    public static function createBlock($contractData)
    {
        $data = json_encode($contractData);
        return [
            'hash' => hash('sha256', $data . now()),
            'timestamp' => now()->toDateTimeString(),
            'data' => $contractData,
        ];
    }
}
