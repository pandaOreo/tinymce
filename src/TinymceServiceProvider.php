<?php

/**
 * @Author       : Jinghua Fan
 * @Date         : 2022-01-12 15:52:56
 * @LastEditors  : Jinghua Fan
 * @LastEditTime : 2022-01-17 19:03:37
 * @Description  : 佛祖保佑,永无BUG
 */


namespace PandaOreo\Tinymce;

use Dcat\Admin\Extend\ServiceProvider;
use Dcat\Admin\Admin;
use Dcat\Admin\Form;
use PandaOreo\Tinymce\Http\Controllers\TinymceController;

class TinymceServiceProvider extends ServiceProvider
{
    protected $js = [];
    protected $css = [];

    protected $menu = [
        [
            'title' => 'tinymce编辑器',
            'uri' => 'tinymce',
            'icon' => '', // 图标可以留空
        ],
    ];

    public function register()
    {
        //
    }

    public function init()
    {
        parent::init();


        if ($views = $this->getViewPath()) {
            $this->loadViewsFrom($views, 'tinymce');
        }

        Admin::booting(function () {
            Form::extend('tinymce', TinymceController::class);
        });
    }

    public function settingForm()
    {
        return new Setting($this);
    }
}
