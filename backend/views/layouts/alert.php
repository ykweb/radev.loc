<?php
use yii\helpers\Html;
use kartik\growl\Growl;
?>

<?php foreach (Yii::$app->session->getAllFlashes() as $message):; ?>
    <?php
    Yii::info('Alert: '.$message['title'].' '.$message['message']);
    echo Growl::widget([
        'type' => (!empty($message['type'])) ? $message['type'] : 'info',
        'title' => (!empty($message['title'])) ? Html::encode($message['title']): '',
        'icon' => (!empty($message['icon'])) ? $message['icon'] : '',
        'body' => (!empty($message['message'])) ? Html::encode($message['message']) : '',
        'showSeparator' => true,
        'delay' => 1, //This delay is how long before the message shows
        'pluginOptions' => [
            'delay' => (!empty($message['duration'])) ? $message['duration'] : 3000, //This delay is how long the message shows for
            'placement' => [
                'from' => (!empty($message['positonY'])) ? $message['positonY'] : 'top',
                'align' => (!empty($message['positonX'])) ? $message['positonX'] : 'right',
            ]
        ]
    ]);
    ?>
<?php endforeach; ?>

