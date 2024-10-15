<?php
namespace app\controllers;

use app\requests\CalculateRequest;
use app\services\CryptocurrencyService;
use yii\data\ActiveDataProvider;
use Yii;
use app\models\Cryptocurrency;
use yii\rest\Controller;
use yii\web\NotFoundHttpException;
use yii\web\BadRequestHttpException;

class CryptocurrencyController extends Controller
{
    protected CryptocurrencyService $cryptocurrencyService;

    public function __construct($id, $module, $config = [])
    {
        $this->cryptocurrencyService = new CryptocurrencyService();
        parent::__construct($id, $module, $config);
    }

    public function actionIndex(): array {
        $pageSize = Yii::$app->request->get('per-page', 100);
        $page = Yii::$app->request->get('page', 1);
        $cacheKey = "cryptocurrencies_page_{$page}_size_{$pageSize}";

        $dataProvider = Yii::$app->cache->get($cacheKey);

        if ($dataProvider === false) {
            $query = Cryptocurrency::find();

            $dataProvider = new ActiveDataProvider([
                'query' => $query,
                'pagination' => [
                    'pageSize' => $pageSize,
                    'page' => $page - 1,
                ],
            ]);

            Yii::$app->cache->set($cacheKey, $dataProvider, 60);
        }

        return [
            'pagination' => [
                'totalCount' => $dataProvider->getTotalCount(),
                'pageSize' => $dataProvider->pagination->pageSize,
                'page' => $dataProvider->pagination->page + 1,
                'pageCount' => $dataProvider->pagination->pageCount,
            ],
            'items' => $dataProvider->getModels(),
        ];
    }

    /**
     * @throws NotFoundHttpException
     */
    public function actionView($symbol): Cryptocurrency {
        $crypto = Cryptocurrency::findOne(['symbol' => $symbol]);

        if (!$crypto) {
            throw new NotFoundHttpException("Cryptocurrency not found");
        }

        return $crypto;
    }

    public function actionUpdate(): array {
        $this->cryptocurrencyService->updateCryptocurrencies();

        return [
            'status' => 'success',
        ];
    }

    /**
     * @throws BadRequestHttpException
     */
    public function actionCalculate(): array {
        $requestData = Yii::$app->request->post();
        $request = new CalculateRequest();
        $request->attributes = $requestData;

        if (!$request->validate()) {
            throw new BadRequestHttpException(json_encode($request->getErrors()));
        }

        return $this->cryptocurrencyService->calculate($request->symbol, $request->amount, $request->currency);
    }
}
