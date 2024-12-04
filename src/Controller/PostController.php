<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostType;
use App\Repository\PostRepository;
use App\Services\FileUploader;
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
    public function create(Request $req, FileUploader $fileUploader, EntityManagerInterface $em): Response {
        $post = new Post();
        //$post->setTitle('This is a title');
        $form = $this->createForm(type: PostType::class, data: $post);

        $form->handleRequest($req);
        //$form->getErrors();

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var \Symfony\Component\HttpFoundation\File\UploadedFile $file */
            $file = $req->files->get(key: 'post')['attachment'];

            if($file) {
                $filename = $fileUploader->uploadFile(
                    file: $file, 
                    uploads_dir: $this->getParameter(name: 'uploads_dir')
                );

                $post->setImage($filename);
            }

            $em->persist($post);
            $em->flush();

            // send a flash success message
            $this->addFlash(type: 'success', message: 'Post was created');

            return $this->redirect($this->generateUrl('post.index'));
        }

        return $this->render('post/create.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route(path: '/show/{id}', name: 'show')]
    public function show(Post $post): Response
    {
        // for this also pass in id and repository as function parameter, but no need as Post is injected and the post will be found by id
        //$post = $postRepository->findPostWithCategory($id);

        return $this->render('post/show.html.twig', [
            'post' => $post,
            'uploads' => '/public/uploads/'
        ]);
    }

    #[Route(path: '/delete/{id}', name: 'delete')]
    public function remove(Post $post, EntityManagerInterface $em): Response
    {
        $em->remove($post);
        $em->flush();

        // send a flash success message
        $this->addFlash(type: 'success', message: 'Post was deleted');

        return $this->redirect($this->generateUrl('post.index'));
    }
}
