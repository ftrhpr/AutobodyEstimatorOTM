<?php
/**
 * Home Controller
 * Handles homepage and language switching
 */

class HomeController extends BaseController
{
    public function index(): void
    {
        if (Session::isLoggedIn()) {
            $this->redirect('/dashboard');
        }

        $this->render('home/index', [
            'title' => __('app_name') . ' - ' . __('welcome'),
        ]);
    }

    public function setLanguage(string $lang): void
    {
        $availableLocales = ['ka', 'en'];

        if (in_array($lang, $availableLocales)) {
            Lang::setLocale($lang);
        }

        $this->back();
    }
}
