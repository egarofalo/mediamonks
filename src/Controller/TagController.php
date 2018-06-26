<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Entity\Tag;
use App\Form\TagType;
use Knp\Component\Pager\PaginatorInterface;
use App\Repository\TagRepository;

class TagController extends AbstractController
{    
    /**
     * @Route(
     *      "/admin/tags/{page}",
     *      name="admin_tags_list",
     *      requirements={
     *          "page"="\d+"
     *      }
     * )
     */
    public function listTags(PaginatorInterface $paginator, TagRepository $tagRepo, SessionInterface $session, $page = 1)
    {
        $pagination = $paginator->paginate($tagRepo->findTags(), (int) $page, Tag::numTagsPerPage);
        $session->set('admin_tags_list_page', $page);
        return $this->render('tag/list.html.twig', [
            'pagination' => $pagination
        ]);
    }
    
    /**
     * @Route(
     *      "/admin/tag/create",
     *      name="admin_tag_create"
     * )
     */
    public function create(Request $request)
    {
        $tag = new Tag();
        $form = $this->createForm(TagType::class, $tag);
        
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($tag);
            $em->flush();
            $this->addFlash('success', $this->renderView('tag/create-alert-success.html.twig'));
            return $this->redirectToRoute('admin_tags_list');
        } 
                
        return $this->render('tag/create.html.twig', [
            'form' => $form->createView()
        ]);
    }
    
    /**
     * @Route(
     *      "/admin/tag/edit/{tag_id}",
     *      name="admin_tag_edit",
     *      requirements={
     *          "tag_id"="\d+"
     *      }
     * )
     */    
    public function edit(Request $request, TagRepository $tagRepo, SessionInterface $session, $tag_id)
    {
        $tag = $tagRepo->findActiveTagById($tag_id);
        
        if (!$tag) {
            throw $this->createNotFoundException("Tag {$tag_id} not found");
        }
 
        $clonedTag = clone $tag;
        $form = $this->createForm(TagType::class, $tag);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if (!$tag->isEquals($clonedTag)) {
                $em = $this->getDoctrine()->getManager();
                $em->flush();
                $this->addFlash('success', $this->renderView('tag/edit-alert-success.html.twig'));
                return $this->redirectToRoute('admin_tags_list', [
                    'page' => $session->get('admin_tags_list_page')
                ]);
            }
            
            // you must change a value
            $this->addFlash('warning', $this->renderView('tag/edit-alert-warning.html.twig'));
        }
        
        return $this->render('tag/edit.html.twig', [
            'form' => $form->createView(),
            'tag' => $tag
        ]);
    }
    
    /**
     * @Route(
     *      "/admin/tag/delete/{tag_id}",
     *      name="admin_tag_delete",
     *      requirements={
     *          "tag_id"="\d+"
     *      }
     * )
     */    
    public function delete(Request $request, TagRepository $tagRepo, SessionInterface $session, $tag_id)
    {
        $submittedToken = $request->request->get('token');

        if (!$this->isCsrfTokenValid('delete-tag', $submittedToken)) {
            $this->addFlash('danger', $this->renderView('csrf-token-invalid.html.twig'));
        } else {
            $em = $this->getDoctrine()->getManager();
            $tag = $tagRepo->findActiveTagById($tag_id);
            if (!$tag) {
                throw $this->createNotFoundException("Tag {$tag_id} not found");
            }
            $tag->setActive(false);
            $em->flush();
        }
        
        return $this->redirectToRoute('admin_tags_list', [
            'page' => $session->get('admin_tags_list_page')
        ]);
    }
    
    /**
     * @Route(
     *      "/admin/tag/restore/{tag_id}",
     *      name="admin_tag_restore",
     *      requirements={
     *          "tag_id"="\d+"
     *      }
     * )
     */
    public function restore(Request $request, SessionInterface $session, $tag_id)
    {
        $submittedToken = $request->request->get('token');

        if (!$this->isCsrfTokenValid('restore-tag', $submittedToken)) {
            $this->addFlash('danger', $this->renderView('csrf-token-invalid.html.twig'));
        } else {
            $em = $this->getDoctrine()->getManager();
            $tag = $em->find(Tag::class, $tag_id);
            if (!$tag) {
                throw $this->createNotFoundException("Tag {$tag_id} not found");
            }
            if ($tag->getActive() === true) {
                throw $this->createNotFoundException("Tag {$tag_id} is already restored");
            }
            $tag->setActive(true);
            $em->flush();
        }
        
        return $this->redirectToRoute('admin_tags_list', [
            'page' => $session->get('admin_tags_list_page')
        ]);        
    }    
    
    /**
     * @Route(
     *      "/api/blog/tags",
     *      name="api_blog_tags"
     * )
     */
    public function apiTags(TagRepository $tagRepo)
    {
        $tags = $tagRepo->findAll();
        
        return $tags;
    }    
}
