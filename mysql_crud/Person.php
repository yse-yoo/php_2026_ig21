<?php
class Person {
    public $name = "";
    public $gender = "";
    public $age = 0;
    public $hp = 100;

    public function __construct($name, $gender)
    {
        $this->name = $name;
        $this->gender = $gender;
    }

    public function talk() {
        echo "わたしは{$this->name}です。";
    }

    public function plusAge() {
        $this->age++;
    }

    public function eat() {
        if ($this->hp < 100) $this->hp += 10;
    }

    public function walk() {
        if ($this->hp > 0) $this->hp - 1;
    }

}