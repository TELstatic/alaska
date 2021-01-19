<?php

namespace App\Admin\Controllers;

use Dcat\Admin\Layout\Content;
use Dcat\Admin\Widgets\Card;
use Dcat\Admin\Widgets\Markdown;
use App\Http\Controllers\Controller;

class MarkdownController extends Controller
{
    public function readme(Content $content)
    {
        return $content
            ->header('开发者中心')
            ->description('文档')
            ->body(Card::make(new Markdown($this->content())));
    }

    public function content()
    {
        return file_get_contents(view('readme')->getPath());
    }
}
