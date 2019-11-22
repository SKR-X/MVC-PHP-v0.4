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
        $this->viewPage("404Page.php", array(
            "view" => "404View",
            "title" => "404 Page",
            "css" => "Err",
            "header" => "404 Page",
            "menu" => "none"
        ));
    }

    public function actionLogin()
    {
        $this->viewPage("AdminLoginPage.php", array(
            "view" => "AdminLoginView",
            "title" => "Login",
            "css" => "Admin",
            "header" => "Login",
            "menu" => "none"
        ));
    }

    public function actionPanel()
    {
        if ($this->Session->sessionCheck("ADMIN")) {
            $this->viewPage("AdminPanelPage.php", array(
                "view" => "AdminPanelView",
                "title" => "Panel",
                "css" => "Admin",
                "header" => "Panel",
                "menu" => "none"
            ));
        } else {
            $this->viewPage("404Page.php", array(
                "view" => "404View",
                "title" => "404 Page",
                "css" => "Err",
                "header" => "404 Page",
                "menu" => "none"
            ));
        }
    }

    public function actionCheckPostLogin()
    {
        if ($_POST['adminlog'] == "1" && $_POST['adminpass'] == "1") {
            $this->Session->sessionStart("ADMIN");
            header("location: /admin/panel");
        } else {
            $this->viewPage("PostInfoLoginPage.php", array(
                "view" => "PostInfoLoginView",
                "title" => "Error",
                "css" => "Admin",
                "header" => "Input error",
                "menu" => "none"
            ));
        }
    }

    public function actionCheckPostPanel()
    {
        if ($this->AdminModel->createNewPage($_POST['urlName'], $_POST['champName'], $_POST['description'])) {
            header("Location: /admin/panel");
        } else {
            header("Location: /");
        }
    }
}
