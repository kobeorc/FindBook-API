<?php

use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    private $categories = [
        'Классика',
        'Современная проза',
        'Поэзия',
        'Драматургия',
        'Детские книги',
        'Бизнес книги',
        'Любовные романы',
        'Детективы',
        'Фантастика',
        'Фэнтэзи',
        'Религия',
        'Юмор',
        'Психология',
        'Наука',
        'Образование',
        'Мотивация',
        'Справочники',
        'Публицистика',
        'Приключения',
        'Дом',
        'Семья',
        'Искусство',
        'Школьная литература',
        'Зарубежная литература',
        'Боевики',
        'Повести',
        'Рассказы'
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->categories as $category) {
            \App\Models\Category::create(['name'=>$category]);
        }

//        factory(\App\Models\Category::class, 10)->create();
    }
}
