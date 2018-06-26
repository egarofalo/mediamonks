<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Knp\Component\Pager\PaginatorInterface;
use App\Repository\PostRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Entity\Post;
use Symfony\Component\HttpFoundation\Request;
use App\Form\PostType;

class PostController extends AbstractController
{   
    /**
     * @Route(
     *      "/",
     *      name="home"
     * )
     */    
    public function home(PostRepository $postRepo)
    {
        $posts = $postRepo->findPosts(false);
        
        return $this->render('index.html.twig', [
            'posts' => $posts
        ]);
    }
    
    /**
     * @Route(
     *      "/post/{post_id}",
     *      name="post_page",
     *      requirements={
     *          "post_id"="\d+"
     *      }
     * )
     */    
    public function show(PostRepository $postRepo, $post_id)
    {
        $post = $postRepo->findPostById($post_id);
        
        if (!$post) {
            throw $this->createNotFoundException("Post {$post_id} not found");
        }
        
        return $this->render('post/post-page.html.twig', [
            'post' => $post
        ]);
    }
    
    /**
     * @Route(
     *      "/admin/posts/{page}",
     *      name="admin_posts_list",
     *      requirements={
     *          "page"="\d+"
     *      }
     * )
     */
    public function listPosts(PaginatorInterface $paginator, PostRepository $postRepo, SessionInterface $session, $page = 1)
    {
        $pagination = $paginator->paginate($postRepo->findPosts(), (int) $page, Post::numPostsPerPage);
        $session->set('admin_posts_list_page', $page);
        
        if ($pagination->getCurrentPageNumber() > $pagination->getPageCount()) {
            return $this->redirectToRoute('admin_posts_list', [
                'page' => $pagination->getPageCount()
            ]);
        }
        
        return $this->render('post/list.html.twig', [
            'pagination' => $pagination
        ]);
    }    
    
    /**
     * @Route(
     *      "/admin/post/create",
     *      name="admin_post_create"
     * )
     */
    public function create(Request $request)
    {
        $post = new Post();
        $form = $this->createForm(PostType::class, $post);
        
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $post->setUser($this->getUser());
            $em->persist($post);
            $em->flush();
            $this->addFlash('success', $this->renderView('post/create-alert-success.html.twig'));
            return $this->redirectToRoute('admin_posts_list');
        } 
                
        return $this->render('post/create.html.twig', [
            'form' => $form->createView()
        ]);
    }
    
    /**
     * @Route(
     *      "/admin/post/edit/{post_id}",
     *      name="admin_post_edit",
     *      requirements={
     *          "post_id"="\d+"
     *      }
     * )
     */     
    public function edit(Request $request, PostRepository $postRepo, SessionInterface $session, $post_id)
    {
        $post = $postRepo->findPostById($post_id);
        
        if (!$post) {
            throw $this->createNotFoundException("Post {$post_id} not found");
        }
 
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            $this->addFlash('success', $this->renderView('post/edit-alert-success.html.twig'));
            return $this->redirectToRoute('admin_posts_list', [
                'page' => $session->get('admin_posts_list_page')
            ]);
        }
        
        return $this->render('post/edit.html.twig', [
            'form' => $form->createView(),
            'post' => $post
        ]);        
    }
    
    /**
     * @Route(
     *      "/admin/post/delete/{post_id}",
     *      name="admin_post_delete",
     *      requirements={
     *          "post_id"="\d+"
     *      }
     * )
     */
    public function delete(Request $request, PostRepository $postRepo, SessionInterface $session, $post_id)
    {
        $submittedToken = $request->request->get('token');

        if (!$this->isCsrfTokenValid('delete-post', $submittedToken)) {
            $this->addFlash('danger', $this->renderView('csrf-token-invalid.html.twig'));
        } else {
            $em = $this->getDoctrine()->getManager();
            $post = $postRepo->findPostById($post_id);
            if (!$post) {
                throw $this->createNotFoundException("Post {$post_id} not found");
            }
            
            $post->removeAllTags();
            $em->remove($post);
            $em->flush();
        }
        
        return $this->redirectToRoute('admin_posts_list', [
            'page' => $session->get('admin_posts_list_page')
        ]);
    }
    
    /**
     * @Route(
     *      "/api/blogs",
     *      name="api_blog_posts"
     * )
     */
    public function apiPosts(PostRepository $postRepo)
    {
        $posts = $postRepo->findApiPosts();
        
        return $posts;
    }
    
    /**
     * @Route(
     *      "/api/blogs/{id}",
     *      name="api_blog_post",
     *      requirements={
     *          "id"="\d+"
     *      }
     * )
     */    
    public function apiPost(PostRepository $postRepo, $id)
    {
        $post = $postRepo->findApiPostById($id);
        
        if (!$post) {
            throw $this->createNotFoundException("Post {$id} not found");
        }
        
        return $post;
    }
}
