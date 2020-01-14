<?php

namespace App\Controllers;

use App\Core\Controller as Controller;

use App\Models\AdminModel as AdminModel;

use App\Core\Session as Session;

class AdminController extends Controller
{

    private $AdminModel;
    private $Session;

    public function __construct()
    {
        $this->AdminModel = new AdminModel();
        $this->Session = new Session();
    }

    public function actionIndex()
    {
        $this->viewPage('404Page.php', '404');
    }

    public function actionLogin()
    {
        $this->viewPage('AdminLoginPage.php', 'loginADM');
    }

    public function actionPanel()
    {
        if ($this->Session->sessionCheck('ADMIN')) {
            $this->viewPage('AdminPanelPage.php', 'panelADM');
        } else {
            $this->viewPage('404Page.php', '404');
        }
    }

    public function actionCheckPostLogin()
    {
        if (isset($_POST['adminlog']) && $_POST['adminlog'] == '1' && $_POST['adminpass'] == '1') {
            $this->Session->sessionStart('ADMIN');
            header('location: /admin/panel');
        } else {
            $this->viewPage('PostInfoLoginPage.php', 'loginInfoADM');
        }
    }

    public function actionCheckPostPanel()
    {
        if (empty($_POST)) {
            header('Location: /admin/panel');
        } elseif (!empty($_POST['urlName'])) {
            if (!$this->AdminModel->takeAllFromTableWhereEqually("champpages", "UrlName", $_POST['urlName'])) {
                $this->AdminModel->createTablesChamp($_POST['urlName']);
                $this->AdminModel->insertArray('champpages', array(
                    'UrlName' => $_POST['urlName']
                ));
                header('Location: /admin/panel?response=SUCCESS');
            } else {
                header('Location: /admin/panel?response=ERROR');
            }
        } elseif (!empty($_POST['urlNameDel'])) {
            if (
                $this->AdminModel->deleteTables($_POST['urlNameDel'])
            ) {
                header('Location: /admin/panel?response=SUCCESS');
            } else {
                header('Location: /admin/panel?response=ERROR');
            }
        }
    }
}
