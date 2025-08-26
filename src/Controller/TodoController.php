<?php

namespace App\Controller;

use App\Core\View;
use App\Model\Todo;
use Exception;

class TodoController
{
    public function showTodosPage() : void
    {
        try {
            $todos = Todo::getAllUndone();
            View::render('pages.todos', [
                'todos' => $todos
            ]);
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
}
