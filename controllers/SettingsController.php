<?php

namespace app\controllers;

use app\models\Module;
use yii\data\ActiveDataProvider;

class SettingsController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Module::find(),
            'pagination' => [
                'pageSize' => 20,
            ]
        ]);


        return $this->render('index', ['provider' => $dataProvider]);
    }

}
