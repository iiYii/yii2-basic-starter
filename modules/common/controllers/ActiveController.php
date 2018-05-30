<?php

namespace app\modules\common\controllers;

use yii\filters\Cors;
use yii\helpers\ArrayHelper;
use Yii;
use yiier\helpers\SearchModel;

class ActiveController extends \yii\rest\ActiveController
{
    const MAX_PAGE_SIZE = 100;

    // 序列化输出
    public $serializer = [
        'class' => 'yii\rest\Serializer',
        'collectionEnvelope' => 'items',
    ];

    public function behaviors()
    {
        return ArrayHelper::merge([
            'corsFilter' => [
                'class' => Cors::className(),
                'cors' => [
                    // restrict access to
                    'Origin' => ['http://localhost', '*'],
//                    'Access-Control-Request-Method' => ['POST', 'PUT'],
                    // Allow only POST and PUT methods
                    'Access-Control-Request-Headers' => ['X-Wsse'],
                    // Allow only headers 'X-Wsse'
                    'Access-Control-Allow-Credentials' => true,
                    // Allow OPTIONS caching
//                    'Access-Control-Max-Age' => 3600,
                    // Allow the X-Pagination-Current-Page header to be exposed to the browser.
                    'Access-Control-Expose-Headers' => ['X-Pagination-Current-Page'],
                ],

            ],
        ], parent::behaviors());
    }

    public function actions()
    {
        $actions = parent::actions();
        $actions['index']['prepareDataProvider'] = [$this, 'prepareDataProvider'];
        return $actions;
    }

    /**
     * @return \yii\data\ActiveDataProvider
     */
    public function prepareDataProvider()
    {
        $modelClass = $this->modelClass;
        $pageSize = request('pageSize') ? (request('pageSize') < self::MAX_PAGE_SIZE ? request('pageSize') : self::MAX_PAGE_SIZE) : 20;
        $searchModel = new SearchModel(
            [
                'defaultOrder' => ['id' => SORT_DESC],
                'model' => $modelClass,
                'scenario' => 'default',
                'pageSize' => $pageSize
            ]
        );

        return $searchModel->search(['SearchModel' => Yii::$app->request->queryParams]);
    }

    /**
     * 当前用户只能操作自己的信息
     * @param string $action
     * @param object $model
     * @param array $params
     * @throws \yii\web\ForbiddenHttpException
     */
    public function checkAccess($action, $model = null, $params = [])
    {
        if (in_array($action, ['view', 'delete', 'update'])) {
            if (isset($model->user_id) && $model->user_id !== \Yii::$app->user->id) {
                throw new \yii\web\ForbiddenHttpException('You can only ' . $action . ' articles that you\'ve created.');
            }
        }
    }

    /**
     * @return array
     */
    public function actionError()
    {
        $exception = Yii::$app->errorHandler->exception;
        if ($exception !== null) {
            return [
                'exception' => $exception->getMessage(),
                'line' => $exception->getLine(),
                'file' => $exception->getFile(),
            ];
        }
//        return 'Not Found';
    }
}
