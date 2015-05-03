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


    public function application() {

        $status = $this->app->request->get('status') ?: 0;

        $applications = Application::where('status', '=', $status)
            ->with('position')
            ->with('languages')
            ->with('languages')
            ->orderBy('created_at', 'desc')
            ->get();

        $this->app->render('admin/career.application.twig', [

            'menu_item'     => 'career',
            'applications'  => $applications,
        ]);
    }

    public function applicationForm($id) {

        $application = Application::where('id', '=', $id)
            ->with('position')
            ->with('languages')
            ->with('languages')
            ->orderBy('created_at', 'desc')
            ->first();

        if ($application == false) {
            $this->app->response->redirect('/admin');
        }

        switch ($application->status) {

            case 2:
                $application->status = 'Reddedildi';
                break;
            case 1:
                $application->status = 'Onaylandı';
                break;

            case 0:
                $application->status = 'Yeni Başvuru';
        }


        $app = [];
        foreach ($this->getAppMapArr() as $key => $val) {

            $app[] = [
                'field' => $key,
                'label' => $val,
                'value' => $application->{$key},
            ];
        }

        $languages = ApplicationLanguage
                    ::where('application_id', '=', $id)
                    ->with('list_language')
                    ->get();

        $educations = ApplicationEducation
                    ::where('application_id', '=', $id)
                    ->get();

        $this->app->render('admin/career.application.form.twig', [

            'menu_item' => 'career',
            'app'       => $app,
            'languages' => $languages,
            'educations'=> $educations,
        ]);
    }


    public function position() {

        $positions = Position::orderBy('ordernum')
            ->get();

        $this->app->render('admin/career.position.twig', [

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

        $this->app->render('admin/career.position.form.twig',
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

        $data['detail_tr'] = strip_tags($data['detail_tr']);
        $data['detail_en'] = strip_tags($data['detail_en']);

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


    private function getAppMapArr() {

        return [
            //'position_id' int(10) unsigned => '',
            'name' => 'İsim',
            'title' => 'Ünvan',
            'birthplace' => 'Doğum Yeri',
            'birthdate' => 'Doğum Tarihi',
            'nation' => 'Uyruk',
            'tc_number' => 'Tc No',
            'gender'   => 'Cinsiyet',
            'conditon'   => 'Medeni Durum',
            'children'  => 'Çocuk Sayısı',
            'children_detail'   => 'Çocuk Detay',
            'couple_name' => 'Eş İsmi',
            'couple_job' => 'Eş MEsleği',
            'military'   => 'Askerlik Durumu',
            'clean_record'  => 'Adli Sicil Kaydı',
            'driving_licence'  => 'Ehliyet',
            'driving_class'  => 'Ehliyet Sınıfı',
            'adress' => 'Adres',
            'city' => 'İlçe',
            'state' => 'İl',
            'gsm'   => 'Gsm',
            'tel'   => 'Tel',
            'email' => 'E-posta',
            'skill_office_tools'  => 'Ofis Gereçleri',
            'skill_computer_grade' => 'Bilgisayar Derecesi',
            'skill_computer_os' => 'İşletim Sistemi Derecesi',
            'skill_computer_detail'  => 'Bilgisayar Becerisi Detayı',
            'status' => 'Başvuru Durumu',
            'cv_path' => 'CV',
        ];
    }
}