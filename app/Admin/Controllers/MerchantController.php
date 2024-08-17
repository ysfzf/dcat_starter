<?php

namespace App\Admin\Controllers;

use App\Enums\AvailabilityEnum;
use App\Models\MerchantModel;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Show;
use Dcat\Admin\Http\Controllers\AdminController;

class MerchantController extends AdminController
{
    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Grid::make(new MerchantModel(), function (Grid $grid) {

            $grid->column('name');
            $grid->column('logo')->image('',80);
            $grid->column('fee');
            $grid->column('expired_at');
            $grid->column('status')->switch();
            $grid->column('contact_name');
            $grid->column('contact_mobile');
            $grid->column('updated_at');

            $grid->filter(function (Grid\Filter $filter) {
                $filter->expand()->panel();
                $filter->like('name')->width(4);
                $filter->like('contact_name')->width(4);

            });
        });
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     *
     * @return Show
     */
    protected function detail($id)
    {
        return Show::make($id, new MerchantModel(), function (Show $show) {
            $show->field('id');
            $show->field('sn');
            $show->field('name');
            $show->field('logo');
            $show->field('fee');
            $show->field('expired_at');
            $show->field('status');
            $show->field('contact_name');
            $show->field('contact_mobile');
            $show->field('created_at');
            $show->field('updated_at');
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Form::make(new MerchantModel(), function (Form $form) {

            $form->hidden('sn');
            $form->text('name');
            $form->image('logo')->required()->accept('jpg,png,gif,jpeg')
                ->uniqueName()->saveFullUrl()->autoUpload()->removable(false)->retainable();
            $form->currency('fee')->required();
            $form->date('expired_at')->required()->help('在设置日期的零点开始停止服务');
            $form->radio('status')->options(AvailabilityEnum::descriptions())->default(1);
            $form->text('contact_name');
            $form->mobile('contact_mobile');

            $form->saving(function(Form $form){
                if(!$form->contact_name){
                    $form->contact_name='';
                }
                if(!$form->contact_mobile){
                    $form->contact_mobile='';
                }
                if($form->isCreating()){
                    $form->sn=md5(time().rand(100,10000));
                }
            });
        });
    }
}
