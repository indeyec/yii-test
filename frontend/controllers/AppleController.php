<?php

namespace frontend\controllers;

use app\models\Apple;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use Yii;
use yii\web\BadRequestHttpException;

/**
 * AppleController implements the CRUD actions for Apple model.
 */
class AppleController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors(): array
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::class,
                    'actions' => [
                        'delete' => ['POST'],
                        'fall' => ['POST'],
                        'eat' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all Apple models.
     *
     * @return string
     */
    public function actionIndex(): string
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Apple::find(),
            'pagination' => [
                'pageSize' => 50,
            ],
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC,
                ],
            ],
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Apple model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView(int $id): string
    {
        $model = $this->findModel($id);
        return $this->render('view', [
            'model' => $model,
        ]);
    }

    /**
     * Creates a new Apple model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|Response
     */
    public function actionCreate()
    {
        $model = new Apple();

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Apple model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate(int $id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Apple model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete(int $id): Response
    {
        $this->findModel($id)->delete();
        return $this->redirect(['index']);
    }

    /**
     * Makes the apple fall to the ground.
     * @param int $id ID
     * @return Response
     * @throws NotFoundHttpException
     * @throws BadRequestHttpException
     */
    public function actionFall(int $id): Response
    {
        $model = $this->findModel($id);

        if ($model->status === Apple::STATUS_EATEN) {
            throw new BadRequestHttpException('Cannot fall, the apple is already eaten.');
        }

        $model->fallToGround();

        return $this->redirect(['view', 'id' => $model->id]);
    }

    /**
     * Eats a portion of the apple.
     * @param int $id ID
     * @param float $percent Percentage to eat
     * @return Response
     * @throws NotFoundHttpException
     * @throws BadRequestHttpException
     */
    public function actionEat(int $id): Response
    {
        $model = $this->findModel($id);

        Yii::info($model->testMethod(), __METHOD__);

        $percent = Yii::$app->request->post('percent');

        if ($percent === null) {
            throw new BadRequestHttpException('Missing required parameter: percent');
        }

        $percent = (float) $percent;

        Yii::info("Apple status: " . $model->status, __METHOD__);
        Yii::info("Is apple rotten: " . ($model->isRotten() ? "Yes" : "No"), __METHOD__);

        if (!$model->canEat()) {
            throw new BadRequestHttpException('Cannot eat the apple in its current state.');
        }

        $model->eat($percent);

        // Обновить статус и удалить яблоко, если оно съедено
        $model->updateStatusAndDeleteIfEaten();

        return $this->redirect(['view', 'id' => $model->id]);
    }

    /**
     * Renders the form for making the apple fall to the ground.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionFallForm(int $id): string
    {
        $model = $this->findModel($id);
        return $this->render('fall', ['model' => $model]);
    }

    /**
     * Renders the form for eating a portion of the apple.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionEatForm(int $id): string
    {
        $model = $this->findModel($id);
        return $this->render('eat', ['model' => $model]);
    }

    /**
     * Finds the Apple model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Apple the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel(int $id): Apple
    {
        if (($model = Apple::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
