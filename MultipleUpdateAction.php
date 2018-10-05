<?php
namespace mickgeek\actionbar;

use mickgeek\actionbar\Widget;
use Yii;
use yii\base\Action;
use yii\base\InvalidConfigException;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;
use yii\helpers\Url;

/**
 * Class MultipleUpdateAction
 * @package mickgeek\actionbar
 */
class MultipleUpdateAction extends Action
{
    /**
     * @var string the model class name. This property must be set.
     */
    public $modelClass;

    /**
     * @var string the primary key name.
     */
    public $primaryKey = 'id';

    /**
     * @var string|array the URL to be redirected to after deleting.
     */
    public $redirectUrl;

    /**
     * Runs the action.
     *
     * @throws NotFoundHttpException the models is not found.
     */
    public function run()
    {
        $ids = Yii::$app->request->post('ids');

        if(!$ids){
            Yii::$app->getSession()->setFlash('error', 'Items is not selected.');
            $this->redirect();
            Yii::$app->end();
        }

        $post = ArrayHelper::cleanArray(Yii::$app->request->post());

        /* @var $modelClass \yii\db\ActiveRecord */
        $modelClass = $this->modelClass;

        /** @var Product $model */
        foreach ($modelClass::find()->where([$this->primaryKey => $ids])->each() as $model) {
            $model->load($post);
            $model->save();
        }

        return $this->redirect();
    }

    /**
     * Redirects the browser to the previous page or the specified URL from [[redirectUrl]].
     */
    public function redirect()
    {
        $previous = Url::previous(Widget::RETURN_URL_PARAM);

        return !empty($this->redirectUrl) ? $this->controller->redirect($this->redirectUrl)
            : $this->controller->redirect($previous);
    }
}