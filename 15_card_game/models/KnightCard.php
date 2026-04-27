<?php
require_once 'BaseCard.php';

class KnightCard extends BaseCard
{
    public function __construct()
    {
        parent::__construct(
            '炎の騎士',
            25,
            15,
            100,
            2,
            '火',
            'KnightCard.png',
            'ブレイズソード',
            45
        );
    }
}
