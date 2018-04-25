<?php
/* @var $this yii\web\View */
?>

<div class="x_panel">
    <div class="x_title">
        <h2>
            <i class="fa fa-folder"></i>
            Available Modules
        </h2>
        <div class="clearfix"></div>
    </div>
    <div class="x_content modules-wrapper">
        <div class="row">
            <div class="col-md-8">
                <button class="btn btn-primary pull-right install-selected" disabled>Install selected</button>
            </div>
        </div>

        <div class="row">
            <div class="col-md-8">
                <?= \yii\grid\GridView::widget([
                    'dataProvider' => $provider,
                    'columns' => [
                        [
                            'attribute' => 'name'
                        ],
                        [
                            'attribute' => 'installed',
                            'format' => 'boolean'
                        ],
                        [
                            'attribute' => 'enabled',
                            'format' => 'boolean'
                        ],
                        [
                            'class' => 'yii\grid\ActionColumn',
                            'contentOptions' => [
                                'class' => 'action-column'
                            ],
                            'header' => 'Actions',
                            'template' => '{install} {enable} {uninstall} {disable}',
                            'buttons' => [
                                'install' => function ($url, $model, $key) {
                                    return "<a href='/module/install?id=$model->id' class='btn btn-primary'>Install</a>";
                                },
                                'enable' => function ($url, $model, $key) {
                                    return "<a href='/module/enable/?id=$model->id' class='btn btn-success'>Enable</a>";
                                },
                                'uninstall' => function ($url, $model, $key) {
                                    return "<a data-module-id='$model->id' href='/module/uninstall?id=$model->id' class='btn btn-danger uninstall'>Uninstall</a>";
                                },
                                'disable' => function ($url, $model, $key) {
                                    return "<a href='/module/disable/?id=$model->id' class='btn btn-warning'>Disable</a>";
                                }
                            ],
                            'visibleButtons' => [
                                'install' => function ($model, $key, $index) {
                                    return !$model->installed;
                                },
                                'enable' => function ($model, $key, $index) {
                                    return $model->installed && !$model->enabled;
                                },
                                'uninstall' => function ($model, $key, $index) {
                                    return $model->installed;
                                },
                                'disable' => function ($model, $key, $index) {
                                    return $model->installed && $model->enabled;
                                }
                            ]
                        ],
                        [
                            'class' => 'yii\grid\CheckboxColumn',
                            'cssClass' => 'check-module',
                            'headerOptions' => [
                                'class' => 'check-column'
                            ],
                            'contentOptions' => [
                                'class' => 'check-column'
                            ],
                        ]
                    ]
                ]) ?>
            </div>
        </div>

    </div>
</div>

