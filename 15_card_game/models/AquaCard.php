<?php
require_once 'BaseCard.php';

class AquaCard extends BaseCard
{
    public function __construct()
    {
        parent::__construct(
            '水の精霊',
            18,
            25,
            100,
            3,
            '水',
            'AquaCard.png',
            'ハイドロポンプ',
            35
        );
    }
}
