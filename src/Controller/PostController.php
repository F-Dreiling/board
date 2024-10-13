<?php

namespace App\Controller;

use App\Entity\Post;
use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;

#[Route('/post', name: 'post.')]
class PostController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(PostRepository $postRepository): Response
    {
        $posts = $postRepository->findAll();

        return $this->render('post/index.html.twig', [
            'posts' => $posts
        ]);
    }

    #[Route('/create', name: 'create')]
    public function create(Request $req, EntityManagerInterface $em): Response {
        $post = new Post();
        $post->setTitle('This is a title');

        $em->persist($post);
        $em->flush();

        return $this->redirect($this->generateUrl('post.index'));
    }

    #[Route(path: '/show/{id}', name: 'show')]
    public function show($id, Post $post): Response
    {
        return $this->render('post/show.html.twig', [
            'post' => $post
        ]);
    }

    #[Route(path: '/delete/{id}', name: 'delete')]
    public function remove($id, Post $post, EntityManagerInterface $em): Response
    {
        $em->remove($post);
        $em->flush();

        $this->addFlash(type: 'success', message: 'Post was deleted');

        return $this->redirect($this->generateUrl('post.index'));
    }
}
