<?php
require_once __DIR__ . '/../interfaces/CardInterface.php';

class BaseCard implements CardInterface
{
    public int $level = 1;
    public string $name = '';
    public int $attack = 0;
    public int $defense = 0;
    public int $hp = 0;
    public int $maxHp = 0;
    public int $mp = 0;
    public int $maxMp = 0;
    public int $exp = 0;
    public string $element = '';
    public string $image = '';
    public string $specialSkill = '';
    public int $specialSkillPower = 0;

    /**
     * 子クラスから送られてきた値でプロパティを初期化する
     */
    public function __construct(
        string $name,
        int $attack,
        int $defense,
        int $hp,
        int $mp,
        string $element,
        string $image,
        string $specialSkill,
        int $specialSkillPower
    ) {
        $this->level = 1;
        $this->exp = 0;
        $this->name = $name;
        $this->attack = $attack;
        $this->defense = $defense;
        $this->hp = $hp;
        $this->maxHp = $hp;
        $this->mp = $mp;
        $this->maxMp = $mp;
        $this->element = $element;

        // 画像パスの処理: URLでなければ ./images/ フォルダを参照する
        if (str_starts_with($image, 'http')) {
            $this->image = $image;
        } else {
            $this->image = 'images/' . $image;
        }

        $this->specialSkill = $specialSkill;
        $this->specialSkillPower = $specialSkillPower;
    }

    /**
     * 通常攻撃
     */
    public function attack(BaseCard $target): int
    {
        // ダメージ計算: (攻撃力 * 1.5 - 相手の防御力) + 乱数
        $baseDmg = ($this->attack * 1.5) - $target->defense;
        $random = rand(-5, 5);
        $dmg = (int)($baseDmg + $random);

        // 最低ダメージ保証 (攻撃力の 20%)
        $minDmg = (int)($this->attack * 0.2);
        if ($dmg < $minDmg) $dmg = $minDmg;

        // TODO: ターゲットのHPからダメージを引く

        // HPが0以下にならないようにする
        if ($target->hp < 0) $target->hp = 0;

        // TODO: ダメージを返す
        return 0;
    }

    /**
     * スキル攻撃
     */
    public function specialSkill(BaseCard $target): int
    {
        if ($this->mp <= 0) return 0;
        $this->mp--;

        // スキルダメージ: (スキル威力 * 1.2 - 相手の防御力 * 0.5)
        $baseDmg = ($this->specialSkillPower * 1.2) - ($target->defense * 0.5);
        $random = rand(-10, 10);
        $dmg = (int)($baseDmg + $random);

        if ($dmg < 0) $dmg = 0;
        // ターゲットのHPからダメージを引く
        $target->hp -= $dmg;

        // HPが0以下にならないようにする
        if ($target->hp < 0) $target->hp = 0;

        // ダメージを返す
        return $dmg;
    }

    /**
     * 経験値を獲得
     */
    public function gainExp(int $exp): void
    {
        // 経験値を加算
        $this->exp += $exp;
    }

    /**
     * レベルアップ
     */
    public function levelUp(): void
    {
        $this->level++;
        $this->attack += 5;
        $this->defense += 3;
        $this->specialSkillPower += 6;
        $this->maxHp += 15;
        $this->hp += 15;
        $this->maxMp += 1;
        $this->mp = min($this->mp + 1, $this->maxMp);
    }
}
