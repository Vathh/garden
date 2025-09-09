<?php

namespace App\Controller;

use App\Core\Auth;
use App\Core\View;
use App\Model\Todo;
use DateTime;
use Exception;
use JetBrains\PhpStorm\NoReturn;

class TodoController
{
    public function showTodosPage() : void
    {
        Auth::requireAuth();

        if (isset($_GET['msg'])) {
            switch ($_GET['msg']) {
                case "title":
                    echo "<script>alert('Pozostawiono puste pole tytuł.');</script>";
                    break;
                case "success":
                    echo "<script>alert('Pomyślnie dodano zadanie.');</script>";
                    break;
            }
        }
        try {
            $todos = Todo::getAllUndone();
            View::render('pages.todos', [
                'todos' => $todos
            ]);
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    /**
     * @throws \DateMalformedStringException
     */
    #[NoReturn] public function add(): void
    {
        Auth::requireAuth();

        $title = $_POST['title'] ?? '';
        $deadlineString = $_POST['deadline'] ?? '';

        if ($title === '') {
            header('Location: /todos?msg=title');
            exit;
        }

        $deadline = null;
        if ($deadlineString !== '') {
            $dl = new DateTime($deadlineString);
            $deadline = $dl->format('Y-m-d');
        }

        Todo::add($title, $deadline);

        header('Location: /todos?msg=success');
        exit;
    }

    #[NoReturn] public function setDone(): void
    {
        Auth::requireAuth();

        $id = (int)$_POST['id'] ?? '';
        $redirect = $_POST['redirect'] ?? '/todos';
        if ($id > 0) {
            Todo::setDone($id);
        }
        header("Location: {$redirect}");
        exit;
    }
}
