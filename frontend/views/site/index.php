<?php
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $this yii\web\View */
use kartik\grid\GridView;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
$this->title = 'My Yii Application';
?>
<div class="row">
    <div class="col-md-12">
        <?= GridView::widget([
            'id' => 'kv-grid-demo',
            'dataProvider' => $dataProvider,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                'title',
                [
                    'class' =>'\kartik\grid\ExpandRowColumn',
                    'value'=>function ($model, $key, $index,$column) {
                        return GridView::ROW_COLLAPSED;
                    },
                    'detailUrl'=> \yii\helpers\Url::to(['site/object-detail']),
                ],
            ],
            'responsive'=>true,
            'hover'=>true

            ]);
        ?>

    </div>

</div>
