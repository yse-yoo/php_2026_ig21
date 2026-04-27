<?php
require_once 'BaseCard.php';

class ForestCard extends BaseCard
{
    public function __construct()
    {
        parent::__construct(
            '森の守護者',
            22,
            20,
            100,
            2,
            '風',
            'ForestCard.png',
            'リーフストーム',
            40
        );
    }
}
