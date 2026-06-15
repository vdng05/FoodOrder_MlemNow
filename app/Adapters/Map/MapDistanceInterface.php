<?php

namespace App\Adapters\Map;

interface MapDistanceInterface
{
    /**
     * Tính toán khoảng cách giữa 2 địa chỉ
     * @return float Khoảng cách tính bằng Km
     */
    public function getDistanceInKm($originAddress, $destinationAddress): float;
}