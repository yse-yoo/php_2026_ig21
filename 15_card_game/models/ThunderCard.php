<?php
require_once 'BaseCard.php';

class ThunderCard extends BaseCard
{
    public function __construct()
    {
        parent::__construct(
            '雷の召喚獣',
            30,
            10,
            100,
            1,
            '雷',
            'ThunderCard.png',
            'ボルテッカー',
            50
        );
    }
}
