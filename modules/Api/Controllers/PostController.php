<?php

namespace Modules\Api\Controllers;

use Base\Services\PostServiceDB;
use Quantum\Factory\ServiceFactory;
use Quantum\Factory\ViewFactory;
use Quantum\Mvc\Qt_Controller;
use Quantum\Hooks\HookManager;
use Quantum\Http\Response;
use Quantum\Http\Request;

class PostController extends Qt_Controller
{

    public $view;
    public $postService;
    public $csrfVerification = false;

    public function __before(ServiceFactory $serviceFactory, ViewFactory $view)
    {
        $this->view = $view;
        $this->postService = $serviceFactory->get(PostServiceDB::class);

        $this->view->setLayout('layouts/main');
    }

    public function getPosts(Response $response)
    {
        $posts = $this->postService->getPosts();
        $response->json($posts);
    }

    public function getPost(Response $response, $id)
    {
        try {
            $post = $this->postService->getPost($id);
            $response->json($post);
        } catch (\Exception $e) {
            return json_encode($e->getMessage());
        }
    }

    public function amendPost( Request $request, Response $response, $id = null)
    {
        $post = [
            'title' => $request->get('title'),
            'content' => $request->get('content'),
        ];

        if ($id) {
            $this->postService->updatePost($id, $post);
            $response->json(['status' => 'updated successfuly']);
        } else {
            $this->postService->addPost($post);
            $response->json(['status' => 'created successfuly']);
        }
    }

    public function deletePost(Response $response, $id)
    {
        $this->postService->deletePost($id);
        $response->json(['status' => 'deleted successfuly']);
    }

}
