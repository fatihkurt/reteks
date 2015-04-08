<?php

namespace App\Controller\Admin;

use App,
    App\Model\Page,
    App\Model\PageCategory,
    App\Model\PageTranslation,
    App\Plugin\AjaxResponse;

class PageCategoryController extends App\Controller\Admin\ControllerBase
{

    use AjaxResponse;

    public function index() {

        $items = PageCategory::with('pages')
            ->orderBy('ordernum')
            ->get();

        $this->app->render('admin/page_category.twig', [

            'menu_item' => 'category',
            'categories'=> $items,
        ]);
    }


    public function delete() {

        $id = $this->app->request->delete('id');

        $success = false;

        if ($id > 0 && $page = PageCategory::find($id)) {

            $success = $page->delete();

            $this->app->flash('success', 'Silme işlemi başarıyla gerçekleştirildi.');
        }

        $this->jsonResponse($success);
    }


    public function edit($id, $category=null) {

        $category = $category
            or
        $category = PageCategory::with('pages.contents')->find($id);

        if ($category == null) {

            return $this->app->response->redirect('/admin/category');
        }

        $this->app->render('admin/page_category.form.twig',
        [
            'menu_item' => 'category',
            'category'  => $category,
            'footer_js' => ['vendor/jquery/jquery.form.min.js', 'admin/category.js']
        ]);
    }

    public function create() {

        $this->edit(null, new PageCategory);
    }


    public function save() {

        $data = $this->app->request->post();

        $category = PageCategory::firstOrCreate(['id' => $data['id']]);

        $category->name_tr = $data['name_tr'];
        $category->name_en = $data['name_en'];
        $category->default_page_id = $data['default_page_id'];
        $category->ordernum = $data['ordernum'];

        $success = $category->save();

        if ($success) {
            $this->app->flash('success', 'Kayıt işlemi başarılıyla gerçekleştirildi.');
        }

        $this->jsonResponse($success, $category->toArray());
    }
}