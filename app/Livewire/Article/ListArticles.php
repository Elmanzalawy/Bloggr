<?php

namespace App\Livewire\Article;

use App\Models\Article;
use Livewire\Attributes\Layout;
use Livewire\Component;

class ListArticles extends Component
{
    public $articles;


    public function mount()
    {
        $this->articles = Article::get();
    }

    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.article.list-articles');
    }
}
