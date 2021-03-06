<?php

namespace api\modules\v1\controllers;

use Yii;
use api\components\BaseController;
use api\models\coupon\CouponLogic;
use api\models\coupon\CouponUsageSearch;
use yii\web\HttpException;

class CouponController extends BaseController
{
    /**
     * 获取优惠券的列表接口
     */
    public function actionIndex()
    {
        $model = new CouponUsageSearch();
        $type = Yii::$app->request->get('type');
        if (is_null($type)) {
            throw new HttpException(421, '请求参数缺失');
        }
        $model->type = $type;
        return $model->search();
    }

    /**
     * 根据控制器参数 领取优惠券
     */
    public function actionExchange()
    {
        $code = Yii::$app->request->post('code');
        if (is_null($code)) {
            throw new HttpException(417, '请输入优惠券码');
        }

        $model = new CouponLogic();
        return $model->exchange($code);
    }
}

