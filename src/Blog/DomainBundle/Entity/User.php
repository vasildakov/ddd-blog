<?php

namespace Blog\DomainBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Blog\DomainBundle\Exception\DomainException;
use Doctrine\ORM\PersistentCollection;

class User
{
    private $id;

    private $login;

    private $passwordHash;

    /**
     * @var ArrayCollection|PersistentCollection|Post[]
     */
    private $posts;

    public function __construct($login, $password)
    {
        $this->login = $login;
        $this->changePasswordTo($password);
        $this->posts = new ArrayCollection();
    }

    public function changePasswordTo($newPassword)
    {
        $this->passwordHash = md5($newPassword);
    }

    public function checkPassword($password)
    {
        return $this->passwordHash === md5($password);
    }

    public function addPost(Post $post)
    {
        if ($post->getAuthor() !== $this) {
            throw new DomainException('Author is not allowed');
        }
        if ($this->posts->contains($post)) {
            return;
        }
        $this->posts->add($post);
    }

    public function removePost(Post $post)
    {
        if (!$this->posts->contains($post)) {
            throw new DomainException('Post is not exists');
        }
        $this->posts->removeElement($post);
    }

    //<editor-fold desc="gets/sets">
    public function getId()
    {
        return $this->id;
    }

    public function getLogin()
    {
        return $this->login;
    }

    public function getPosts()
    {
        return $this->posts;
    }
    //</editor-fold>
}