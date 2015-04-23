<?php

namespace App\Controller\Admin;

use App,
    App\Model\CareerPosition as Position,
    App\Model\CareerApplication as Application,
    App\Model\CareerApplicationLanguage as ApplicationLanguage,
    App\Model\CareerApplicationEducation as ApplicationEducation,
    App\Model\ListLanguage,
    App\Plugin\AjaxResponse;

class CareerController extends App\Controller\Admin\ControllerBase
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

    public function position() {

        $positions = Position::orderBy('ordernum')
            ->get();

        $this->app->render('admin/application.position.twig', [

            'menu_item' => 'career',
            'positions' => $positions,
        ]);
    }

    public function positionEdit($id, $position=null) {

        $position = $position
            OR
        $position = Position::find($id);


        if ($position == null) {

            $this->flash->error('Posizyon bulunamadı.');

            return $this->app->response->redirect('/admin/application/position');
        }

        $this->app->render('admin/application.position.form.twig',
                [
                    'menu_item' => 'career',
                    'position'  => $position,
                    'footer_js' => ['vendor/jquery/jquery.form.min.js', 'ckeditor/ckeditor.js', 'ckeditor/adapters/jquery.js', 'admin/application.js']
                ]);
    }


    public function positionDelete() {

        $id = $this->app->request->delete('id');

        $success = false;

        if ($id > 0 && $page = Position::find($id)) {

            $success = $page->delete();

            $this->app->flash('success', 'Silme işlemi başarıyla gerçekleştirildi.');
        }

        $this->jsonResponse($success);
    }


    public function positionNew() {

        $this->positionEdit(null, new Position);
    }


    public function positionSave() {

        $data = $this->app->request->post();


        $position = Position::firstOrNew(['id' => $data['id']]);

        $position->bind($data);

        $success = $position->save();

        if ($success) {
            $this->app->flash('success', 'Kayıt işlemi başarılıyla gerçekleştirildi.');
        }

        $this->jsonResponse($success, $position->toArray());
    }



    private function saveGallery(& $page, $galleryArr) {


        foreach ($galleryArr as $idx=>$galleryD) {

            $gallery = PageGallery::firstOrNew(['id' => $galleryD['id']]);


            if (! isset($_FILES["gallery_image$idx"]) && $gallery->image == '') {

                continue;
            }
            else
            if (isset($_FILES["gallery_image$idx"])) {

                $image = $this->imageUpload($_FILES["gallery_image$idx"], 'upload/gallery');

                if (isset($image['error']) && $image['error'] == false) {

                    $this->flash->error($image['error']);
                    continue;
                }

                $gallery->image   = $image['name'];;
            }

            $gallery->page_id = $page->id;

            $gallery->ordernum= $galleryD['order'];

            $gallery->save();
        }
    }


    private function imageUpload($file, $path='upload/page') {

        $img = array();

        $validImageTypes = ['image/jpeg' => 'jpg', 'image/png' => 'png'];

        if ($file['error'] === 0) {

            if (in_array($file['type'], array_keys($validImageTypes))) {

                $name = uniqid('img-'.date('Ymd').'-') . '.' . $validImageTypes[$file['type']];

                $filePath = PUB_DIR . $path . '/' . $name;

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