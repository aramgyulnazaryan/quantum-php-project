<?php

/**
 * Quantum PHP Framework
 *
 * An open source software development framework for PHP
 *
 * @package Quantum
 * @author Arman Ag. <arman.ag@softberg.org>
 * @copyright Copyright (c) 2018 Softberg LLC (https://softberg.org)
 * @link http://quantum.softberg.org/
 * @since 1.9.0
 */

namespace Base\Services;

use Base\models\PostModel;
use Quantum\Factory\ModelFactory;
use Quantum\Mvc\Qt_Service;


/**
 * Class PostServiceDB
 * @package Base\Services
 */
class PostServiceDB extends Qt_Service
{
    public static $posts = [];

    private $postModel;

    public function __init(ModelFactory $modelFactory)
    {
        $this->postModel = $modelFactory->get(PostModel::class);
    }

    public function getPosts()
    {
        $posts = $this->postModel->get();
        return $posts;
    }

    public function getPost($id)
    {
        $post = $this->postModel->findOne($id)->asArray();
        return $post;
    }

    public function addPost($data)
    {
        $post = $this->postModel->create();
        $post->title = $data['title'];
        $post->content = $data['content'];
        $post->save();
    }

    public function updatePost($id, $data)
    {
        $post = $this->postModel->findOne($id);

        foreach ($data as $key => $value) {
            $post->$key = $value;
        }

        $post->save();
    }

    public function deletePost($id)
    {
        $post = $this->postModel->findOne($id);
        $post->delete();
    }
}
