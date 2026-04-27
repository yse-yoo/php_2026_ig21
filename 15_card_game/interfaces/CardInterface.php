<?php

interface CardInterface
{
    public function attack(BaseCard $target): int;
    public function specialSkill(BaseCard $target): int;
    public function gainExp(int $exp): void;
    public function levelUp(): void;
}
