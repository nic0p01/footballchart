<?php
use app\models\Invoice;

$this->title = 'Личный кабинет продаж';
$count = count($model);
$earned = $count*590;
$payed = Yii::$app->user->identity->payed;
$remnant = $earned-$payed;
$referer_link = Yii::$app->user->identity->referer_link;
?>

<div class="cabinet_wrapper">
    <div class="row mb30">
        <div class="col-md-3">Количество продаж: <?= $count ?></div>
        <div class="col-md-3">Заработанная сумма: <?= $earned ?></div>
        <div class="col-md-6">
            <div class="col-md-4">Выплачено: <?= $payed ?></div>
            <div class="col-md-4">Остаток: <?= $remnant ?></div>
            <div class="col-md-4">referer=<?= $referer_link ?></div>
        </div>
    </div>
    <table class="table-stripped">

        <thead>
            <tr>
                <th>дата</th>
                <th>заказ</th>
                <th>utm_source</th>
                <th>utm_medium</th>
                <th>utm_campaign</th>
                <th>utm_term</th>
                <th>utm_content</th>
            </tr>
        </thead>
        <tbody>
    <?php foreach ($model as $item): ?>
    <?php $date = Invoice::find()->where(['id' => $item->invoice_id])->one(); ?>
        <tr>
            <td><?= date('d-m-Y H:m:s', strtotime($date->date)); ?></td>
            <td><?= $item->invoice_id ?></td>
            <td><?= $item->utm_source ?></td>
            <td><?= $item->utm_medium ?></td>
            <td><?= $item->utm_campaign ?></td>
            <td><?= $item->utm_term ?></td>
            <td><?= $item->utm_content ?></td>
        </tr>
    <?php endforeach; ?>
        </tbody>
    </table>

</div>
