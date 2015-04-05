<?php

namespace App\Controller\Admin;

use App,
    App\Model\News,
    App\Model\NewsTranslation,
    App\Plugin\AjaxResponse;

class NewsController extends App\Controller\Admin\ControllerBase
{

    use AjaxResponse;

    public function index() {

        $selectedCategory = $this->app->request->get('cat');

        $news = News::with('contents')
            ->orderBy('start_date')
            ->get();

        $this->app->render('admin/news.twig', [

            'menu_item' => 'news',
            'items'     => $news
        ]);
    }


    public function delete() {

        $id = $this->app->request->delete('id');

        $success = false;

        if ($id > 0 && $news = News::find($id)) {

            $success = $news->delete();

            $this->app->flash('success', 'Silme işlemi başarıyla gerçekleştirildi.');
        }

        $this->jsonResponse($success);
    }


    public function edit($id, $news=null) {

        $news = $news
            or
        $news = News::with('contents')->find($id);

        if ($news == null) {

            return $this->app->response->redirect('/admin/news');
        }

        $this->app->render('admin/news.form.twig',
        [
            'menu_item' => 'news',
            'news'      => $news,
            'langs'     => $this->app->config('languages'),
            'footer_js' => ['vendor/jquery/jquery.form.min.js', 'ckeditor/ckeditor.js', 'admin/news.js']
        ]);
    }

    public function create() {

        $this->edit(null, new News);
    }


    public function save() {

        $data = $this->app->request->post();

        $news = News::firstOrCreate(['id' => $data['id']]);

        $news->start_date   = date('Y-m-d H:i:s', strtotime($data['start_date']));
        $news->end_date     = date('Y-m-d H:i:s', strtotime($data['start_date']));

        foreach ($data['contents'] as $index=>$content) {

            $newsContent = NewsTranslation::firstOrCreate(['news_id' => $news->id, 'lang' => $content['lang']]);

            $newsContent->news_id   = $news->id;
            $newsContent->lang      = $content['lang'];
            $newsContent->title     = $content['title'];
            $newsContent->seo_url   = $content['seo_url'];
            $newsContent->description= $content['description'];
            $newsContent->content   = $content['content'];

            if (! $newsContent->save()) {

                $this->msg = strtoupper($content['lang']) . ' kaydında sorun oluştu lütfen kontrol ediniz.';
            }
        }

        if (isset($_FILES['image'])) {

            $img = $this->imageUpload($_FILES['image']);

            if (isset($img['error'])) {
                $this->app->flash('danger', $img['error']);
            }
            else {
                $news->image = $img['name'];
            }
        }

        //$news->contents()->saveMany($newsContent);

        $success = $news->save();

        if ($success) {
            $this->app->flash('success', 'Kayıt işlemi başarılıyla gerçekleştirildi.');
        }

        $this->jsonResponse($success, $news->toArray());
    }



    private function imageUpload($file) {

        $img = array();

        $validImageTypes = ['image/jpeg' => 'jpg', 'image/png' => 'png'];

        if ($file['error'] === 0) {

            if (in_array($file['type'], array_keys($validImageTypes))) {

                $name = uniqid('img-'.date('Ymd').'-') . '.' . $validImageTypes[$file['type']];

                $filePath = PUB_DIR . 'upload/news/' . $name;

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