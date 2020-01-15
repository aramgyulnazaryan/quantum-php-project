<?php

use PHPUnit\Framework\TestCase;
use Base\Services\PostServiceDB;
use Quantum\Factory\ServiceFactory;

class PostServiceTest extends TestCase
{

    public $postService;

    private $initialPost = [
        'title' => 'Lorem ipsum dolor sit amet',
        'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean condimentum condimentum nibh.',
    ];

    public function setUp(): void
    {
        $this->postService = (new ServiceFactory)->get(PostServiceDB::class);

        $reflectionProperty = new \ReflectionProperty($this->postService, 'posts');
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue($this->postService, []);
    }

    public function tearDown(): void
    {
        $reflectionProperty = new \ReflectionProperty(ServiceFactory::class, 'initialized');
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue(ServiceFactory::class, []);
    }

    public function testAddNewPost()
    {
        $this->postService->addPost([
            'title' => 'Lorem ipsum dolor sit amet',
            'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean condimentum condimentum nibh.',
        ]);

        $this->assertSame(1, count($this->postService->getPosts()));
        $this->assertEquals($this->postService->getPost(1)['title'], 'Lorem ipsum dolor sit amet');
        $this->assertEquals($this->postService->getPost(1)['content'], 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean condimentum condimentum nibh.');
    }

    public function testGetPosts()
    {
        $this->assertIsObject($this->postService);
        $this->assertIsArray($this->postService->getPosts());
        $this->assertNotEmpty($this->postService->getPosts());
        $this->assertSame(1, count($this->postService->getPosts()));
    }

    public function testGetSinglePost()
    {
        $post = $this->postService->getPost(1);
        $this->assertIsArray($post);
        $this->assertArrayHasKey('content', $post);
        $this->assertArrayHasKey('title', $post);
        $this->assertEquals($post['title'], 'Lorem ipsum dolor sit amet');
    }

    public function testUpdatePost()
    {
        $this->postService->updatePost(1, [
            'title' => 'Modified post title',
            'content' => 'Modified post content',
        ]);

        $this->assertNotEquals('Lorem ipsum dolor sit amet', $this->postService->getPost(1)['title']);
        $this->assertEquals('Modified post title', $this->postService->getPost(1)['title']);
        $this->assertEquals('Modified post content', $this->postService->getPost(1)['content']);
    }

    public function testDeletePost()
    {
        $this->postService->deletePost(1);
        $this->assertEquals(0, count($this->postService->getPosts()));
    }

}
