<?php

namespace App;

use App\Hashing\Encoder;
use App\Hashing\Generator;
use App\Http\Request;
use App\Storage\Database;
use App\Storage\LinkStorage;
use App\Validation\CanBeParsedValidator;
use App\Validation\FilterValidator;
use App\Validation\LengthValidator;
use App\Validation\SanitizeValidator;
use App\Validation\SchemeValidator;
use App\Validation\UrlValidator;
use App\View\View;

class App
{
    private Request $req;
    private Generator $gen;
    private LinkStorage $link;
    private View $view;

    public function __construct(Request $request)
    {
        $this->req = $request;
        $this->link = new LinkStorage(new Database());
        $validators = [
            new CanBeParsedValidator(),
            new SchemeValidator(),
            new SanitizeValidator(),
            new FilterValidator(),
            new LengthValidator()
        ];
        $this->gen = new Generator(new UrlValidator($validators), new Encoder(), $this->link);
        $this->view = new View();
    }

    public function processPost(): void
    {
        $this->gen->generateUrlHash($this->req->postGetUrl());

        // At this stage getting Url from Post should be safe because otherwise validation should fail
        $this->link->store($this->gen->getHash(), $this->req->postGetUrl());

        $shortURL = $this->composeShortUrl($this->gen->getHash());
        $srcURL = $this->req->postGetUrl();

        if (!empty($this->gen->getHash())) {
            $this->view->addVar('shortUrl', $shortURL);
            $this->view->addVar('srcUrl', $srcURL);
        }

        if (empty($this->req->postGetUrl()) || empty($this->gen->getHash())) {
            $this->view->addVar('error', $this->gen->getMessage());
        }

        echo $this->view->render();
    }

    public function processGet(): void
    {
        $hash = htmlentities(ltrim($this->req->serverGetUri(), '/'));
        $path = $this->link->getLongUrlByHash($hash);

        if (!empty($path)) {
            $this->req->redirectTo($path);
            return;
        }

        if (empty($hash)) {
            echo $this->view->render();
            return;
        }

        $this->view->error(404);
    }

    private function composeShortUrl(string $hashedUrl): string
    {
        return $this->req->getScheme() . $this->req->getHost() . '/' . $hashedUrl;
    }
}
