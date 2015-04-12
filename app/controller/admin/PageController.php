<?php

namespace App\Controller\Admin;

use App,
    App\Model\Page,
    App\Model\PageCategory,
    App\Model\PageTranslation,
    App\Plugin\AjaxResponse;

class PageController extends App\Controller\Admin\ControllerBase
{

    use AjaxResponse;

    public function index() {

        $selectedCategory = $this->app->request->get('cat');

        $pages = Page::where(function($q) use($selectedCategory) {

                if ($selectedCategory > 0) {

                    $q->where('category_id', '=', $selectedCategory);
                }
            })
            ->with('contents')
            ->orderBy('ordernum')
            ->get();

        $this->app->render('admin/page.twig', [

            'menu_item' => 'page',
            'pages'     => $pages,
            'categories'=> PageCategory::all(),
            'category'  => $selectedCategory,
        ]);
    }


    public function delete() {

        $id = $this->app->request->delete('id');

        $success = false;

        if ($id > 0 && $page = Page::find($id)) {

            $success = $page->delete();

            $this->app->flash('success', 'Silme işlemi başarıyla gerçekleştirildi.');
        }

        $this->jsonResponse($success);
    }


    public function edit($id, $page=null) {

        $page = $page
            or
        $page = Page::with('contents')->find($id);

        if ($page == null) {

            return $this->app->response->redirect('/admin/page');
        }

        $this->app->render('admin/page.form.twig',
        [
            'menu_item' => 'page',
            'tab'       => $this->app->request->get('tab'),
            'page'      => $page,
            'categories'=> PageCategory::all(),
            'langs'     => $this->app->config('languages'),
            'footer_js' => ['vendor/jquery/jquery.form.min.js', 'ckeditor/ckeditor.js', 'ckeditor/adapters/jquery.js', 'admin/page.js']
        ]);
    }

    public function create() {

        $this->edit(null, new Page);
    }


    public function save() {

        $data = $this->app->request->post();

        $page = Page::firstOrNew(['id' => $data['id'], 'category_id' => $data['category_id']]);

        $page->category_id = $data['category_id'];
        $page->ordernum = $data['ordernum'];
        $page->status   = isset($data['status']) ? 1 : 0;

        $page->save();

        foreach ($data['contents'] as $index=>$content) {

            $pageContent = PageTranslation::firstOrCreate(['page_id' => $page->id, 'lang' => $content['lang']]);

            $pageContent->page_id   = $page->id;
            $pageContent->lang      = $content['lang'];
            $pageContent->title     = html_entity_decode($content['title']);
            $pageContent->seo_url   = $content['seo_url'];
            $pageContent->description= html_entity_decode($content['description']);
            $pageContent->content   = html_entity_decode($content['content']);

            if (! $pageContent->save()) {

                $this->msg = strtoupper($content['lang']) . ' kaydında sorun oluştu lütfen kontrol ediniz.';
            }
        }

        if (isset($_FILES['image1'])) {

            $img = $this->imageUpload($_FILES['image1']);

            if (isset($img['error'])) {
                $this->app->flash('danger', $img['error']);
            }
            else {
                $page->image1 = $img['name'];
            }
        }

        if (isset($_FILES['image2'])) {

            $img = $this->imageUpload($_FILES['image2']);

            if (isset($img['error'])) {
                $this->app->flash('danger', $img['error']);
            }
            else {
                $page->image2 = $img['name'];
            }
        }

        //$page->contents()->saveMany($pageContent);

        $success = $page->save();

        if ($success) {
            $this->app->flash('success', 'Kayıt işlemi başarılıyla gerçekleştirildi.');
        }

        $this->jsonResponse($success, $page->toArray());
    }



    private function imageUpload($file) {

        $img = array();

        $validImageTypes = ['image/jpeg' => 'jpg', 'image/png' => 'png'];

        if ($file['error'] === 0) {

            if (in_array($file['type'], array_keys($validImageTypes))) {

                $name = uniqid('img-'.date('Ymd').'-') . '.' . $validImageTypes[$file['type']];

                $filePath = PUB_DIR . 'upload/page/' . $name;

                if (move_uploaded_file($file['tmp_name'],  $filePath) === true) {

                    $img = array('name' => $name);
                }
                else {
                    $img = array('error' => $file['name'] . ' dosyası yüklenemedi');
                }
            }
            else {
                $img = array('error' => $file['name'] . ' dosya formatı geçerli değil. Lütfen jpg veya png dosyası yükleyiniz.');
            }
        }
        else {
            $img = array('error' => $file['error']);
        }

        return $img;
    }
}