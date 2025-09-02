<?php


namespace App\Nova\Actions;

use App\Models\Order;
use App\Library\OnlineHdm;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Illuminate\Support\Collection;

class HandleHdm extends Action
{
public $name = 'Handle HDM';

public function handle(ActionFields $fields, Collection $models)
{
foreach ($models as $model) {
$hdm = new OnlineHdm($model->id);

if ($model->has_hdm) { // флаг в базе, например boolean
$hdm->reverseHdm();
$model->has_hdm = false;
} else {
$hdm->createHdm();
$model->has_hdm = true;
}

$model->save();
}

return Action::message('HDM обработан успешно');
}
}
