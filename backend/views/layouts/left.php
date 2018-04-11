<aside class="main-sidebar">

    <section class="sidebar">

        <?= dmstr\widgets\Menu::widget(
            [
                'options' => ['class' => 'sidebar-menu'],
                'items' => [
                   // ['label' => 'Menu Yii2', 'options' => ['class' => 'header']],
                    ['label' => 'Настройки', 'icon' => 'cog', 'url' => ['/settings']],
                    ['label' => 'Пользователи', 'icon' => 'users', 'url' => ['/users']],
                    ['label' => 'Страницы', 'icon' => 'file-o', 'url' => ['/pages']],
                    ['label' => 'Сообщения', 'icon' => 'comments', 'url' => ['/messages']],
                    // ['label' => 'Рассылка', 'icon' => 'comments', 'url' => ['/email']],
                    [
                        'label' => 'Каталог обьявлений',
                        'icon' => 'database',
                        'url' => '#',
                        'items' => [
                            ['label' => 'Категории', 'icon' => 'list-ol', 'url' => ['/categories'],],
                            ['label' => 'Обьявления', 'icon' => 'list-alt', 'url' => ['/ads'],],
                            ['label' => 'Дополнительные поля', 'icon' => 'thumb-tack', 'url' => ['/fields'],],
                            // ['label' => 'Группы дополнительных полей', 'icon' => 'th', 'url' => ['/catalog/fields-group'],],
                            // ['label' => 'Настройки каталога', 'icon' => 'cogs', 'url' => ['/catalog/settings'],],
                        ],
                    ],
                    [
                        'label' => 'Магазины',
                        'icon' => 'database',
                        'url' => '#',
                        'active' => in_array(Yii::$app->controller->id, ['magazine-categories', 'plan', 'period', 'tarif', 'magazine', 'delivery', 'payment', 'magazine-success', 'magazine-eav-fields', 'magazine-eav-options', 'magazine-eav-value']),
                        'items' => [
                            ['label' => 'Категории', 'icon' => 'thumb-tack', 'url' => ['/magazine-categories'],],
                            ['label' => 'Доп. поля к категориям', 'icon' => 'thumb-tack', 'url' => ['/magazine-eav-fields'],],
                            ['label' => 'Магазины', 'icon' => 'users', 'url' => ['/magazine'],],
                            ['label' => 'Бухгалтерия', 'icon' => 'users', 'url' => ['/magazine-success'],],
                            [
                                'label' => 'Настройки магазинов', 
                                'icon' => 'users',
                                'url' => '#',
                                'active' => in_array(Yii::$app->controller->id, ['plan', 'period', 'tarif', 'delivery', 'payment']),
                                'items' => [
                                    ['label' => 'Планы', 'icon' => 'list-ol', 'url' => ['/plan'],],
                                    ['label' => 'Периоды', 'icon' => 'list-alt', 'url' => ['/period'],],
                                    ['label' => 'Тарифы планов', 'icon' => 'thumb-tack', 'url' => ['/tarif'],],
                                    ['label' => 'Доставка', 'icon' => 'thumb-tack', 'url' => ['/delivery'],],
                                    ['label' => 'Оплата', 'icon' => 'thumb-tack', 'url' => ['/payment'],],
                                ],
                            ],
                        ],
                    ],
                    ['label' => 'Бухгалтерия', 'icon' => 'money', 'url' => ['/promotion']],
                    ['label' => 'Блог', 'icon' => 'life-ring', 'url' => ['/blog']],
                ],
            ]
        ) ?>

    </section>

</aside>
